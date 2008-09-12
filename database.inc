<?php
/**
 * @file
 * Wrapper for database interface code.
 */
$host = 'localhost';
$user = 'marincom_chat';
$pass = 'cH_tT1';
$database = 'marincom_games';

/**
 * Indicates the place holders that should be replaced in _db_query_callback().
 */
define('DB_QUERY_REGEXP', '/(%d|%s|%%|%f|%b)/');

function db_connect(){
    global $active_db;
    global $host, $user, $pass, $database;

    $active_db = @mysql_connect($host, $user, $pass, TRUE, 2);
    mysql_select_db($database, $active_db);
}  

function db_query($query) {
  global $active_db;

  $args = func_get_args();
  array_shift($args);
  if (isset($args[0]) and is_array($args[0])) { // 'All arguments in one array' syntax
    $args = $args[0];
  }
  _db_query_callback($args, TRUE);
  $query = preg_replace_callback(DB_QUERY_REGEXP, '_db_query_callback', $query);

  $result = mysql_query($query, $active_db);
  if (!mysql_errno($active_db)) {
    return $result;
  }
  else {
    trigger_error(check_plain(mysql_error($active_db) ."\nquery: ". $query), E_USER_WARNING);
    return FALSE;
  }
}

function check_plain($text) {
  return validate_utf8($text) ? htmlspecialchars($text, ENT_QUOTES) : '';
}

function validate_utf8($text) {
  if (strlen($text) == 0) {
    return TRUE;
  }
  return (preg_match('/^./us', $text) == 1);
}

/**
 * Helper function for db_query().
 */
function _db_query_callback($match, $init = FALSE) {
  static $args = NULL;
  if ($init) {
    $args = $match;
    return;
  }

  switch ($match[1]) {
    case '%d': // We must use type casting to int to convert FALSE/NULL/(TRUE?)
      return (int) array_shift($args); // We don't need db_escape_string as numbers are db-safe
    case '%s':
      return db_escape_string(array_shift($args));
    case '%%':
      return '%';
    case '%f':
      return (float) array_shift($args);
    case '%b': // binary data
      return db_encode_blob(array_shift($args));
  }
}

function db_result($result, $row = 0) {
  if ($result && mysql_num_rows($result) > $row) {
    return mysql_result($result, $row);
  }
  return FALSE;
}

/**
 * Fetch one result row from the previous query as an object.
 *
 * @param $result
 *   A database query result resource, as returned from db_query().
 * @return
 *   An object representing the next row of the result. The attributes of this
 *   object are the table fields selected by the query.
 */
function db_fetch_object($result) {
  if ($result) {
    return mysql_fetch_object($result);
  }
}

/**
 * Fetch one result row from the previous query as an array.
 *
 * @param $result
 *   A database query result resource, as returned from db_query().
 * @return
 *   An associative array representing the next row of the result. The keys of
 *   this object are the names of the table fields selected by the query, and
 *   the values are the field values for this result row.
 */
function db_fetch_array($result) {
  if ($result) {
    return mysql_fetch_array($result, MYSQL_ASSOC);
  }
}
/**
 * Prepare user input for use in a database query, preventing SQL injection attacks.
 */
function db_escape_string($text) {
  global $active_db;
  return mysql_real_escape_string($text, $active_db);
}

/**
 * Determine how many result rows were found by the preceding query.
 *
 * @param $result
 *   A database query result resource, as returned from db_query().
 * @return
 *   The number of result rows.
 */
function db_num_rows($result) {
  if ($result) {
    return mysql_num_rows($result);
  }
}

db_connect();
