<?php

/**
 * JPages class autoloader
 *
 */

spl_autoload_register('JPagesClassLoader');

/**
 * Handles dynamic loading of classes as registered with spl_autoload_register
 *
 */
function JPagesClassLoader($className) {

	$file = JPAGES_CORE_PATH . "$className.php";

	if(is_file($file)) {
		require($file);
	}
	
}
