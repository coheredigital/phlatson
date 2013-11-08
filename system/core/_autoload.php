<?php

include '_functions.php';
include '_interfaces.php';

/**
 * XPages class autoloader
 * Handles dynamic loading of classes as registered with spl_autoload_register
 */

spl_autoload_register('XPagesClassLoader');
function XPagesClassLoader($className) {

	$coreFile = CORE_PATH."{$className}.php";
	$pluginFile = SYSTEM_PATH."plugins/{$className}.php";
	$fieldFile = SYSTEM_PATH."fieldtypes/{$className}.php";

	if(is_file($coreFile)) require_once($coreFile);
	elseif(is_file($pluginFile)) require_once($pluginFile);
	elseif(is_file($fieldFile)) require_once($fieldFile);

}


/*
 * Setup XPages class autoloads
 */

function XpagesConfig() {

	/*
	 * Define installation paths and urls
	 *
	 */
	$rootPath = ROOT_PATH;
	$siteDir = 'site';
	$systemDir = $rootPath."system/";
	$coreDir = $systemDir."core/";
	$assetsDir = "$siteDir/assets";
	$adminTplDir = 'templates-admin';



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

if ($config->debug) {
	$debugbar = new DebugBar\StandardDebugBar();
	$debugbarRenderer = $debugbar->getJavascriptRenderer("/This/");
	$debugbarRenderer->setBaseUrl(SITE_URL."/system/plugins/DebugBar/Resources");
	$debugbar['time']->startMeasure('pagerender', 'Page Render');
	$debugbar['time']->startMeasure('setup', 'Setup');
}

/*
 * Load and execute XPages
 *
 */

try {

	$XPages = new XPages($config);

	$pages = new Pages();
	$page = new Page();
	$input = new Input();
	$session = new Session();
	$debugbar['time']->stopMeasure('setup');
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// output template
// NOTE: create a better method of achieving this
if (is_file($page->template)) include $page->template;