<?php

function str_remove_prefix ($string, $prefix) 
{
    if (substr($string, 0, strlen($prefix)) == $prefix) {
        $string = substr($string, strlen($prefix));
    } 
    return $string;
}

function flatbedErrorHandler($errno, $errstr, $errfile, $errline) {

    switch ($errno) {
        case 'E_USER_ERROR':
        case 'E_RECOVERABLE_ERROR':
            throw new FlatbedException($errstr, $errno);
            break;

        default:
            # code...
            break;
    }
    return true;
}
set_error_handler('flatbedErrorHandler');

function getMemoryUse() : string
{
    $memory = memory_get_usage() / pow( 1024 , 2 );
    $memory = round( $memory , 2);
    return "{$memory}mb";
}
