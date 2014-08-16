<?php

/**
 * class autoloader
 * Handles dynamic loading of classes as registered with spl_autoload_register
 */


spl_autoload_register('classLoader');
function classLoader($className)
{

    $systemPath = ROOT_PATH . "system/";
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);


    if (is_file($systemPath . $className . ".php")) {
        require_once $systemPath . $className . ".php";
    } else {

        $extensionsSitePath = ROOT_PATH . "site" . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR;

        $extensionSite = $extensionsSitePath . $className . ".php";
        $extensionSystem = $extensionsSystemPath . $className . ".php";
        if (is_file($extensionSite)) {
            require_once $extensionSite;
        } else {
            if (is_file($extensionSystem)) {
                require_once $extensionSystem;
            }
        }
    }


}