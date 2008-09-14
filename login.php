<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);      

    require_once 'database.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';
    trigger_error(print_r($_POST,true), E_USER_NOTICE);

    global $user;
    user_authenticate($_POST['username'], $_POST['pass']);
    $jsonret = array();
    if ($user->user_id >0){
        $jsonret['success'] = true;
        $jsonret['greeting'] = (!empty($user->name) ? $user->name : $user->username);
    }
    else {
        $jsonret['success'] = false;
        $jsonret['errors'] = array();
        $jsonret['errors']['username'] =
            'Account does not exists or password does not match';
    }
    echo json_encode($jsonret);

