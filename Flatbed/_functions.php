<?php
namespace Flatbed;

function str_remove_prefix ($string, $prefix)
{
    if (substr($string, 0, strlen($prefix)) == $prefix) {
        $string = substr($string, strlen($prefix));
    }
    return $string;
}


function getMemoryUse() : string
{
    $memory = memory_get_usage() / pow( 1024 , 2 );
    $memory = round( $memory , 2);
    return "{$memory}mb";
}
