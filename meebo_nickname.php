<?php
    error_reporting(E_ALL);      

    require_once 'database.inc';
    require_once 'game.inc';
    include_once 'error.inc';
    require_once 'user.inc';
    require_once 'session.inc';
    require_once 'meebo_rooms_lib.php';

    global $user;
    trigger_error("meebo_nickname: ".print_r($user,true), E_USER_NOTICE);
 
	header( "Content-Type: text/xml; charset=utf-8" );
    echo( "<?xml version='1.0' encoding='UTF-8'?>\n");
    echo( "<meebouser>\n" );
    if ($user->user_id > 0) {
      echo( "  <nickname>".$user->username."</nickname>\n" );
      echo( "  <iconurl>".$user->thumb_url."</iconurl>\n" );
      echo( "  <profile_url>".$user->profile_url ."</profile_url>\n" );
      echo( "  <profile_url_text>Profile</profile_url_text>\n" );
      echo( "  <profile_message>Link to PHP Manual<a href='http://ca3.php.net/manual/en/'>PHP Manual</a></profile_message>\n" );
    } else {
      echo( "  <nickname>guest_".session_id()."</nickname>\n" );
    }
    echo( "</meebouser>" );

