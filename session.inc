<?php
/**
 * @file
 * User session handling functions.
 */
function sess_open($save_path, $session_name) {
  return TRUE;
}

function sess_close() {
  return TRUE;
}

function sess_read($key) {
  global $user;

  // Write and Close handlers are called after destructing objects since PHP 5.0.5
  // Thus destructors can use sessions but session handler can't use objects.
  // So we are moving session closure before destructing objects.
  register_shutdown_function('session_write_close');

  // Handle the case of first time visitors and clients that don't store cookies (eg. web crawlers).
  if (!isset($_COOKIE[session_name()])) {
    return '';
  }

  // Otherwise, if the session is still active, we have a record of the client's session in the database.
  $user = db_fetch_object(db_query("SELECT u.*, s.* FROM users u INNER JOIN sessions s ON u.user_id = s.user_id WHERE s.session_id = '%s'", $key));

  // We didn't find the client's record (session has expired), or they are an anonymous user.
  if (!$user)  {
    $user = anonymous_user('');
  }

  return $user->session;
}

function sess_write($key, $value) {
  global $user;

  // If saving of session data is disabled or if the client doesn't have a session,
  // and one isn't being created ($value), do nothing.
  if (!session_save_session() || (empty($_COOKIE[session_name()]) && empty($value))) {
    return TRUE;
  }

  $result = db_query("SELECT session_id FROM sessions WHERE session_id = '%s'", $key);

  if (!db_num_rows($result)) {
    // Only save session data when when the browser sends a cookie. This keeps
    // crawlers out of session table. This reduces memory and server load,
    // and gives more useful statistics. We can't eliminate anonymous session
    // table rows without breaking throttle module and "Who's Online" block.
    if ($user->user_id || $value || count($_COOKIE)) {
      db_query("INSERT INTO sessions (session_id, user_id, hostname, session, timestamp) VALUES ('%s', %d, '%s', '%s', %d)", $key, $user->user_id, $_SERVER["REMOTE_ADDR"], $value, time());
    }
  }
  else {
    db_query("UPDATE sessions SET user_id = %d, hostname = '%s', session = '%s', timestamp = %d WHERE session_id = '%s'", $user->user_id, $_SERVER["REMOTE_ADDR"], $value, time(), $key);
  }

  return TRUE;
}

/**
 * Called when an anonymous user becomes authenticated or vice-versa.
 */
function sess_regenerate() {
  $old_session_id = session_id();

  // We code around http://bugs.php.net/bug.php?id=32802 by destroying
  // the session cookie by setting expiration in the past (a negative
  // value).  This issue only arises in PHP versions before 4.4.0,
  // regardless of the Drupal configuration.
  // TODO: remove this when we require at least PHP 4.4.0
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
  }

  session_regenerate_id();

  db_query("UPDATE sessions SET session_id = '%s' WHERE session_id = '%s'", session_id(), $old_session_id);
}

/**
 * Counts how many users have sessions. Can count either anonymous sessions, authenticated sessions, or both.
 *
 * @param int $timestamp
 *   A Unix timestamp representing a point of time in the past.
 *   The default is 0, which counts all existing sessions.
 * @param int $anonymous
 *   TRUE counts only anonymous users.
 *   FALSE counts only authenticated users.
 *   Any other value will return the count of both authenticated and anonymous users.
 * @return  int
 *   The number of users with sessions.
 */
function sess_count($timestamp = 0, $anonymous = true) {
  $query = $anonymous ? ' AND user_id = 0' : ' AND user_id > 0';
  return db_result(db_query('SELECT COUNT(session_id) AS count FROM sessions WHERE timestamp >= %d'. $query, $timestamp));
}

/**
 * Called by PHP session handling with the PHP session ID to end a user's session.
 *
 * @param  string $sid
 *   the session id
 */
function sess_destroy_sid($sid) {
  db_query("DELETE FROM sessions WHERE session_id = '%s'", $sid);
}

/**
 * End a specific user's session
 *
 * @param  string $uid
 *   the user id
 */
function sess_destroy_uid($uid) {
  db_query('DELETE FROM sessions WHERE user_id = %d', $uid);
}

function sess_gc($lifetime) {
  // Be sure to adjust 'php_value session.gc_maxlifetime' to a large enough
  // value. For example, if you want user sessions to stay in your database
  // for three weeks before deleting them, you need to set gc_maxlifetime
  // to '1814400'. At that value, only after a user doesn't log in after
  // three weeks (1814400 seconds) will his/her session be removed.
  db_query("DELETE FROM sessions WHERE timestamp < %d", time() - $lifetime);

  return TRUE;
}

/**
 * Determine whether to save session data of the current request.
 *
 * This function allows the caller to temporarily disable writing of session data,
 * should the request end while performing potentially dangerous operations, such as
 * manipulating the global $user object.
 *
 * @param $status
 *   Disables writing of session data when FALSE, (re-)enables writing when TRUE.
 *
 * @return FALSE if writing session data has been disabled. Otherwise, TRUE.
 */
function session_save_session($status = NULL) {
  static $save_session = TRUE;
  if (isset($status)) {
    $save_session = $status;
  }
  return ($save_session);
}

session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy_sid', 'sess_gc');
session_start();

