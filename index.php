<?php 

define("JPAGES", true);
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('SITE_ROOT', 'XPages/');
define('SITE_DIR', ROOT_DIR."site/");
define('CONTENT_DIR', SITE_DIR."content/");



if(DIRECTORY_SEPARATOR != '/') $rootPath = str_replace(DIRECTORY_SEPARATOR, '/', ROOT_DIR); 


$contentDir = 'content'; 
$coreDir = "system";

/*
 * Setup XPages class autoloads
 */
require("$coreDir/XPages.php");






// include('test.php');

$XPages = new XPages(); 
$page = $XPages->data;
if (is_file($XPages->template)) include $XPages->template;

