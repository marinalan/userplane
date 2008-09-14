<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);      

    require_once 'database.inc';
    require_once 'game.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';

    trigger_error(print_r($_POST,true), E_USER_NOTICE);
    trigger_error(print_r($user,true), E_USER_NOTICE);

    $room_id = assign_room($_POST['game_id'], $_POST['game_name']);
    //$_SESSION['game'] = $_POST['game_name'];

    $_SESSION['room'] = $room_id;
    echo json_encode( array('room_id' => $room_id, 'channel' => $_POST['game_name'].'_'.$room_id) );
