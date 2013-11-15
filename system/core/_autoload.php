<?php



/**
 * class autoloader
 * Handles dynamic loading of classes as registered with spl_autoload_register
 */

spl_autoload_register('setupClassLoader');
function setupClassLoader($className) {

	// find a more consistent method of moving through the posible lovations of classes, maybe a facory class for fieldtype or plugins

	$coreFile = ROOT_PATH."system/core/{$className}.php";
	$pluginFile = ROOT_PATH."system/plugins/{$className}.php";
	$fieldFile = ROOT_PATH."system/fieldtypes/{$className}/{$className}.php";

	if(is_file($coreFile)) require_once($coreFile);
	elseif(is_file($pluginFile)) require_once($pluginFile);
	elseif(is_file($fieldFile)) require_once($fieldFile);

}