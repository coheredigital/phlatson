<?php

// declare (strict_types = 1);

namespace Phlatson;

error_reporting(E_ALL);

// define a few system contants
const PHLATSON = 0001;

define("ROOT_PATH", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__ . "/"));
define("DATA_PATH", ROOT_PATH . "site/");
define("TEMP_PATH", ROOT_PATH . "temp/");

// use composer autoloader
$composer = require_once(ROOT_PATH . 'vendor/autoload.php');
$exceptionHandler = new ErrorHandler();

try {

    $debugbar = new \DebugBar\StandardDebugBar();
    

    $phlatson = new Phlatson();
    $request = new Request();
    $config = new Config('site');
    $page = new Page($request->url);

    // inject into API
    $phlatson->api("request", $request);
    $phlatson->api("debugbar", $debugbar);

    $phlatson->api("config", $config);
    $phlatson->api("pages", new Pages());
    $phlatson->api("views", new Views());
    $phlatson->api("page", $page);


    echo $phlatson->execute();

    
} catch (Exceptions\PhlatsonException $exception) {
    echo $exception->render($config);
}