<?php
// to debug ajax backend....
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    $msg = $errstr.'('.$errfile.','.$errline.')';
    error_log($msg);
}    

set_error_handler("myErrorHandler",E_USER_NOTICE);
