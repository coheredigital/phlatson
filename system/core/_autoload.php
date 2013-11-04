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

	$file = CORE_DIR.$className.".php";

	if(is_file($file)) require($file);


}
