<?php

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

	$coreFile = CORE_DIR."$className.php";
	$fieldFile = SYSTEM_DIR."fields/$className.php";


	if(is_file($coreFile)) require($coreFile);
	elseif(is_file($fieldFile)) require($fieldFile);



}
