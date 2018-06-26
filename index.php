<?php

declare (strict_types = 1);

namespace Phlatson;

error_reporting(E_ALL);


// define a few system contants
define("PLATSON", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR);
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR);


// use composer autoloader
$composer = require_once(VENDOR_PATH . 'autoload.php');

// config ref
\ref::config('expLvl', 2);
\ref::config('maxDepth', 6);

$exceptionHandler = new ErrorHandler();

try {

    $phlatson = new Phlatson;
    $request = new Request();
    $page = new Page($request->url);
    $phlatson->api("page", $page);

    // prepare PHP ini_set options
    ini_set('display_errors', 'On');
    ini_set('session.use_cookies', 'true');
    ini_set('session.use_only_cookies', '1');
    // ini_set("session.gc_maxlifetime", "$this->sessionExpireSeconds");
    ini_set("session.save_path", CACHE_PATH . "/sessions");
    // ini_set("date.timezone", $config->timezone);
    ini_set('default_charset', 'utf-8');


    
    echo $phlatson->execute();

} catch (Exceptions\PhlatsonException $exception) {
    echo $exception->render($config);
}
