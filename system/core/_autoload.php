<?php

/**
 * class autoloader
 * Handles dynamic loading of classes as registered with spl_autoload_register
 */


spl_autoload_register('classLoader');
function classLoader($className)
{
    $systemPath = ROOT_PATH . "system/core/";
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);


    if (is_file($systemPath . $className . ".php")) {
        require_once $systemPath . $className . ".php";
    }
    else {
        $extensionsPath = ROOT_PATH . "system" . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR;
        $extension = $extensionsPath . $className . ".php";
        if (is_file($extension)) {
            require_once($extension);
        }
    }


}