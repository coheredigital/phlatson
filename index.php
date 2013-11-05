<?php

define("JPAGES", true);
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('SYSTEM_DIR', ROOT_DIR.'system/');
define('SITE_ROOT', 'http://localhost/XPages/');
define('SITE_DIR', ROOT_DIR . "site/");
define('CONTENT_DIR', SITE_DIR . "content/");


/*
 * Setup XPages class autoloads
 */



function XpagesConfig() {

	/*
	 * Define installation paths and urls
	 *
	 */
	$rootPath = dirname(__FILE__)."/";
	$siteDir = 'site';
	$systemDir = $rootPath."system/";
	$coreDir = $systemDir."core/";
	$assetsDir = "$siteDir/assets";
	$adminTplDir = 'templates-admin';

	require("{$coreDir}XPages.php");

	/*
	 * Setup configuration data and default paths/urls
	 *
	 */
	$config = new Config();
	/*
	 * Include system and user-specified configuration options
	 *
	 */
	// include("$rootPath/$wireDir/config.php");
	$configFile = "$rootPath/$siteDir/config.php";
	$configFileDev = "$rootPath/$siteDir/config-dev.php";
	@include(is_file($configFileDev) ? $configFileDev : $configFile);

	/*
	 * If debug mode is on then echo all errors, if not then disable all error reporting
	 *
	 */
	if($config->debug) {
		error_reporting(E_ALL ^ E_NOTICE);
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
	// ini_set("session.save_path", rtrim($config->paths->sessions, '/'));

	return $config;
}

$config = XpagesConfig();


/*
 * Load and execute XPages
 *
 */


$XPages = new XPages($config);
$pages = new Pages();
$page = new Page();
if ($page->template) include $page->template;





