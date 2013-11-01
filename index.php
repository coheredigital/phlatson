<?php 

define("JPAGES", true);
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('SITE_ROOT', 'XPages/');
define('SITE_DIR', ROOT_DIR . "site/");
define('CONTENT_DIR', SITE_DIR . "content/");
define("SYSTEM_DIR", ROOT_DIR . 'system/');


if(DIRECTORY_SEPARATOR != '/') $rootPath = str_replace(DIRECTORY_SEPARATOR, '/', ROOT_DIR); 


$contentDir = 'content'; 
$coreDir = "system";

/*
 * Setup XPages class autoloads
 */
require_once( SYSTEM_DIR . "autoload.php"); 
$XPages = new XPages(); 
$config = new Config(); 
$page = new Page(); 
if (is_file($page->template)) include $page->template;