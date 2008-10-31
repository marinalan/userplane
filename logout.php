<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);      

    require_once 'database.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';

    global $user;
    user_logout();
    $jsonret = array();
    if ($user->user_id == 0){
        $jsonret['success'] = true;
    }
    else {
        $jsonret['success'] = false;
    }
    echo json_encode($jsonret);

