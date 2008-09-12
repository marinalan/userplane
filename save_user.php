<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);      

    require_once 'database.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';
    
    trigger_error(print_r($_POST,true), E_USER_NOTICE);
    $result = user_save($_POST);
    // trigger_error(json_encode($result), E_USER_NOTICE);
    echo json_encode($result);
