<?php

declare (strict_types = 1);

namespace Phlatson;

// error_reporting(E_ALL);

// define a few system contants
const PHLATSON = 0001;

define("ROOT_PATH", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__ . "/"));
define("DATA_PATH", ROOT_PATH . "site/");
define("TEMP_PATH", ROOT_PATH . "temp/");

// use composer autoloader
require_once(ROOT_PATH . 'vendor/autoload.php');
// $exceptionHandler = new ErrorHandler();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {

    $phlatson = new Phlatson();
    $finder = new Finder(__DIR__ . "/site/");
    $finder->addPath(__DIR__ . "/site/");
    
    $phlatson->api("finder", new Finder(__DIR__ . "/site/"));

    $models = new Finder();
    $models->addPath("/site/models/");
    $models->addPath("/Phlatson/data/models/");
    $models->setType("Model");

    $phlatson->api("models", $models);
    $phlatson->api('debugbar', new \DebugBar\StandardDebugBar());
    echo $phlatson->execute(new Request());
    
} catch (Exception $exception) {
    echo $exception->render($config);
}