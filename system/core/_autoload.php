<?php

include '_functions.php';
/**
 * JPages class autoloader
 *
 */

spl_autoload_register('XPagesClassLoader');

/**
 * Handles dynamic loading of classes as registered with spl_autoload_register
 *
 */
function XPagesClassLoader($className) {

	$coreFile = CORE_DIR."{$className}.php";
	$pluginFile = SYSTEM_DIR."plugins/{$className}.php";
	$fieldFile = SYSTEM_DIR."fieldtypes/{$className}.php";

	if(is_file($coreFile)) require_once($coreFile);
	elseif(is_file($pluginFile)) require_once($pluginFile);
	elseif(is_file($fieldFile)) require_once($fieldFile);

}
