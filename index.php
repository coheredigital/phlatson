<?php

declare (strict_types = 1);

namespace Phlatson;

error_reporting(E_ALL);


// define a few system contants
const PHLATSON = 0001;
const ROOT_PATH = __DIR__ . DIRECTORY_SEPARATOR;
const DATA_PATH = ROOT_PATH . "site" . DIRECTORY_SEPARATOR;
const TEMP_PATH = ROOT_PATH . "temp" . DIRECTORY_SEPARATOR;
const SYSTEM_DATA = ROOT_PATH . "temp" . DIRECTORY_SEPARATOR;

// use composer autoloader
$composer = require_once(ROOT_PATH . 'vendor/autoload.php');
$exceptionHandler = new ErrorHandler();

try {

    $phlatson = new Phlatson();
    $request = new Request();
    $config = new Config('site');
    $page = new Page($request->url);

    // inject into API
    $phlatson->api("request", $request);

    $phlatson->api("config", $config);
    $phlatson->api("pages", new Pages());
    $phlatson->api("views", new Views());
    $phlatson->api("page", $page);

    // prepare PHP ini_set options
    ini_set('display_errors', 'On');
    ini_set('session.use_cookies', 'true');
    ini_set('session.use_only_cookies', '1');
    // ini_set("session.gc_maxlifetime", "$this->sessionExpireSeconds");
    ini_set("session.save_path", TEMP_PATH . "/sessions");
    ini_set("date.timezone", $config->timezone);
    ini_set('default_charset', 'utf-8');


    echo $phlatson->execute();
    
} catch (Exceptions\PhlatsonException $exception) {
    echo $exception->render($config);
}
