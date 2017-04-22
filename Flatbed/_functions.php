<?php
namespace Flatbed;

function str_remove_prefix ($string, $prefix) 
{
    if (substr($string, 0, strlen($prefix)) == $prefix) {
        $string = substr($string, strlen($prefix));
    } 
    return $string;
}

function flatbedErrorHandler($errno, $errorMessage, $errfile, $errline) {

    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_STRICT:
        case E_ERROR:
        case E_PARSE:
        case E_PARSE:
            throw new Exceptions\FlatbedException($errorMessage, $errno);
            break;

        default:
            // var_dump($errno);
            // throw new Exceptions\FlatbedException($errorMessage, $errno);
            break;
    }
    return true;
}
set_error_handler('Flatbed\flatbedErrorHandler');

function getMemoryUse() : string
{
    $memory = memory_get_usage() / pow( 1024 , 2 );
    $memory = round( $memory , 2);
    return "{$memory}mb";
}
