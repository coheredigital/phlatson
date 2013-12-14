<?php

include '_autoload.php';
include '_functions.php';
include '_interfaces.php';

/*
 * Setup XPages class autoloads
 */

function setupConfig() {

	// start an array of directories
	$directories = array();
	// site directories
	$directories['site'] = 'site/';
	$directories['assets'] = $directories['site'] . 'assets/';
	$directories['content'] = $directories['site'] . 'content/';
	$directories['fields'] = $directories['site'] . 'fields/';
	$directories['templates'] = $directories['site'] . 'templates/';
	$directories['extensions'] = $directories['site'] . 'extensions/';
	$directories['layouts'] = $directories['site'] . 'layouts/';
	$directories['users'] = $directories['site'] . 'users/';
	// system directories
	$directories['system'] = 'system/';
	$directories['admin'] = $directories['system'] . 'admin/';
	$directories['core'] = $directories['system'] . 'core/';
	$directories['systemFields'] = $directories['system'] . 'fields/';
	$directories['systemTemplates'] = $directories['system'] . 'templates/';
	$directories['systemPlugins'] = $directories['system'] . 'extensions/';
	$directories['fieldtypes'] = $directories['system'] . 'extensions/fieldtypes/';


	if(isset($_SERVER['HTTP_HOST'])) {
		$httpHost = strtolower($_SERVER['HTTP_HOST']);
		$rootURL = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/\\") . '/';
	} else {
		$httpHost = '';
		$rootURL = '/';
	}



	/*
	 * Setup configuration data and default paths/urls
	 */
	$config = new Config;
	$urls = new Paths($rootURL); 
	// loop through directories and set key / value 
	foreach ($directories as $key => $value) {
		$urls->{$key} = $value;
	}

	// clone the urls object and change the root
	$paths = clone $urls;
	$paths->root = ROOT_PATH;

	// add the urls anc paths to config
	$config->urls = $urls; 
	$config->paths = $paths; 


	$configFile = $config->paths->site . "/config.php";
	@include($configFile);

	/*
	 * Output errors if debug true, else disable error reporting
	 */
	if($config->debug) {
		error_reporting(E_ALL ^ E_NOTICE);
		// error_reporting(E_ALL);
		ini_set('xdebug.var_display_max_depth', '10');
		ini_set('display_errors', 1);
	} else {
		error_reporting(0);
		ini_set('display_errors', 0);
	}

	/*
	 * Prepare any PHP ini_set options
	 *
	 */
	session_name($config->sessionName);
	ini_set('session.use_cookies', true);
	ini_set('session.use_only_cookies', 1);
	ini_set("session.gc_maxlifetime", $config->sessionExpireSeconds);
	ini_set("session.save_path", rtrim($config->paths->assets.DIRECTORY_SEPARATOR."sessions", '/'));
	ini_set("date.timezone", $config->timezone);
	ini_set('default_charset','utf-8');

	// HTTPS and AJAX 
	$config->https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
	$config->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

	return $config;
}

$config = setupConfig();


/*
 * Load and execute XPages
 *
 */

try {
	Core::init($config);

	foreach (Core::api() as $name => $object) {
		if ($name === "config" || $name === "page") continue; // skip $config, it is already set
		else ${$name} = $object;
	}

	$page = new Page($input->url);

} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}



// output template
// NOTE: create a better method of achieving this

if (is_file($page->layout)) include $page->layout;
