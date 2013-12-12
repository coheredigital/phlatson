<?php

/**
 * class autoloader
 * Handles dynamic loading of classes as registered with spl_autoload_register
 */


spl_autoload_register('classLoader');
function classLoader($className) {


	$namespace = str_replace("\\",DIRECTORY_SEPARATOR,__NAMESPACE__);
    $className = str_replace("\\",DIRECTORY_SEPARATOR,$className);

    $systemPath = ROOT_PATH."system".DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR;

    $namespacePath = empty($namespace) ? "" : $namespace.DIRECTORY_SEPARATOR;

    $coreClass = "{$systemPath}{$namespacePath}{$className}.php";


    // var_dump($fieldtype);

    if(is_file($coreClass)) require_once($coreClass);
    else{
        $pluginsPath = ROOT_PATH."system".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR.$className.DIRECTORY_SEPARATOR;
        $fieldtype = "{$pluginsPath}{$namespacePath}{$className}.php"; 
        if(is_file($fieldtype)) require_once($fieldtype);
    }


}