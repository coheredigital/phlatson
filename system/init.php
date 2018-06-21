<?php 

define('VENDOR_PATH', ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR );
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR );
define('CACHE_PATH', ROOT_PATH . "cache" . DIRECTORY_SEPARATOR );
define('CORE_PATH', SYSTEM_PATH . "core" . DIRECTORY_SEPARATOR );
define('ROOT_URL', "/");

// pre includes some default core file for Phlatson
// files / class that will be REQUIRED for every single request



require_once CORE_PATH . '_functions.php';

// check for composer autoloader
$composer = VENDOR_PATH .'autoload.php'; // composer autoloader
if(file_exists($composer)) require_once($composer);
else require_once CORE_PATH . 'PhlatsonAutoloader.php';

require_once CORE_PATH . '_interfaces.php';

$autoloader = new PhlatsonAutoloader();