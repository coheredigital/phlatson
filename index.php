<?php

declare (strict_types = 1);

namespace Phlatson;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

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
    echo $phlatson->execute();

} catch (Exceptions\PhlatsonException $exception) {
    echo $exception->render($config);
}
