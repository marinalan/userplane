<?php
function anonymous_user($session = '') {
  $user = new stdClass();
  $user->user_id = 0;
  $user->hostname = $_SERVER['REMOTE_ADDR'];
  $user->session = $session;
  return $user;
}

function user_load($array = array()) {
  // Dynamically compose a SQL query:
  $query = array();
  $params = array();

  foreach ($array as $key => $value) {
    if ($key == 'user_id' || $key == 'networkid' || $key == 'networktypeid' || $key == 'active') {
      $query[] = "$key = %d";
      $params[] = $value;
    }
    else if ($key == 'pass') {
      $query[] = "pass = '%s'";
      $params[] = md5($value);
    }
    else {
      $query[]= "LOWER($key) = LOWER('%s')";
      $params[] = $value;
    }
  }
  $result = db_query('SELECT * FROM users u WHERE '. implode(' AND ', $query), $params);

  if (db_num_rows($result)) {
    $user = db_fetch_object($result);
  }
  else {
    $user = FALSE;
  }

  return $user;
}

function user_authenticate($name, $pass) {
  global $user;

  // Try to log in the user locally. Don't set $user unless successful.
  if ( $account = user_load(array('username' => $name, 'pass' => $pass))) {
      $user = $account;
  }
  else {
      $user = anonymous_user();
  }
  return $user;
}

function user_logout(){
    global $user;

    session_destroy();
    $user = anonymous_user();
}

function user_save($array, $if_visitor = true){
    global $user;

    $result = array();
    $str_fields = array('username', 'pass', 'email', 'name', 'phone', 'location');

    $query = ''; $v = array();
    if ( isset($array['user_id']) ){
        $user_id = (int)$array['user_id'];

        if (db_num_rows(db_query(
            "select user_id from users where (username='%s' or email='%s') and user_id <> %d",
                                 $array['username'], $array['email'], $user_id)) > 0){

            $result['success'] = false;
            $result['errors'] = array();
            $result['errors']['username'] = 
                'Somebody else is already registered with this username or email';
                                  
            return $result;
        }  
        foreach ($array as $key => $value) {
          if ($key == 'pass' && !empty($value)) {
            $query .= "$key = '%s', ";
            $v[] = md5($value);
          }
          else if ($key != 'pass' && in_array($key, $str_fields)){
            $query .= "$key = '%s', ";
            $v[] = $value;
          }
          else if ($key == 'birthday' && !empty($value) && 
                   ereg("([0-9]{2})/([0-9]{2})/([0-9]{4})", $value, $regs)) {
            // we expect date to be as formatted as string: mm/dd/yyyy
            $query .= "$key = '%s', ";
            $v[] = "$regs[3]-$regs[1]-$regs[2]";
          }
        }
        trigger_error("UPDATE users SET $query WHERE uid = %d", E_USER_NOTICE);
        db_query("UPDATE users SET $query WHERE uid = %d", array_merge($v, array($user_id)));
        $result['success'] = true;
    }
    else {
      if (db_num_rows(db_query("select user_id from users where username='%s' or email='%s'",
                               $array['username'], $array['email'])) > 0){

          $result['success'] = false;
          $result['errors'] = array();
          $result['errors']['username'] = 
              'Somebody else is already registered with this username or email';
                                
          return $result;
      }  

      foreach ($array as $key => $value) {
        switch ($key) {
          case 'pass':
            $fields[] = $key;
            $values[] = md5($value);
            $s[] = "'%s'";
            break;
          case 'networkid':     case 'networktypeid':
            $fields[] = $key;
            $values[] = $value;
            $s[] = "%d";
            break;
          case 'birthday':  
            if (!empty($value) && 
                   ereg("([0-9]{2})/([0-9]{2})/([0-9]{4})", $value, $regs)) {
            // we expect date to be as formatted as string: mm/dd/yyyy
            $fields[] = $key;
            $values[] = "$regs[3]-$regs[1]-$regs[2]";
            $s[] = "'%s'";
          }  
            break;  
          default:
            if (in_array($key, $str_fields)) {
              $fields[] = $key;
              $values[] = $value;
              $s[] = "'%s'";
            }
            break;
        }
      }
      trigger_error('INSERT INTO users ('. implode(', ', $fields) .', dateaddded, active) VALUES ('. implode(', ', $s) .', now(), 1)', E_USER_NOTICE);

      db_query('INSERT INTO users ('. implode(', ', $fields) .', dateaddded, active) VALUES ('. implode(', ', $s) .', now(), 1)', $values);
      $result['success'] = true;
    }

    if ($if_visitor){
      $user = user_load(
          array('username' => $array['username'], 'email' => $array['email']));
      trigger_error(print_r($user, true), E_USER_NOTICE);

      if (!empty($array['pass'])) {
        sess_destroy_uid($user->user_id);
        sess_regenerate();
      }
      $_SESSION['user_id'] = $user->user_id;
    }
    return $result;
}
