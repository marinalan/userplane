<?php
    error_reporting(E_ALL);      

    require_once 'database.inc';
    require_once 'game.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';
    require_once 'meebo_rooms_lib.php';

    trigger_error(print_r($_POST,true), E_USER_NOTICE);
    $node = $_POST['meebo_node'];
/*
    $url = MEEBO_API_BASE."getwidgetembedcode?apikey=".MEEBO_API_KEY.
      "&node=$node";
    echo open_https_url($url,false,true);
 */
    $sql = 
      "select widget, room, w, h 
       from   meebo_rooms
       where  node='".db_escape_string($node)."'";
    $result = db_query($sql);    
    $rows = array();
    $row = db_fetch_array($result);
    if ($row) {
      echo json_encode( array( 'stat' => 'ok', 'data' => $row ) );
    } else {
      echo json_encode( array( 
        'stat' => 'error', 
        'message' => 'This room is no more available for chat.' ) );
    }
