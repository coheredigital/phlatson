<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';
require_once ROOT_PATH . 'system/_interfaces.php';
require_once ROOT_PATH . 'system/_traits.php';



$app = new App(new Registry);

/* instatiate api variables */
$app->service('config', new Config);
$app->service('request', new Request);

// set default request behaviour
$pagesRoute = new Route();
$pagesRoute
    ->path(":all")
    ->callback("Pages.render");

$app->service('router', new Router($pagesRoute));

// setup config routes
foreach (registry("config")->routes as $r){
    $route = new Route($r);
    registry("router")->add($route);
}

$app->service('extensions', new Extensions);
$app->service('sanitizer', new Sanitizer);
$app->service('pages', new Pages);
$app->service('users', new Users);
$app->service('fields', new Fields);
$app->service('templates', new Templates);
$app->service('session', new Session);
$app->service('logger', new Logger);
$app->service('events', new Events);


// execute the app
try {
    registry('router')->run($registry->request);
} catch(Exception $e) {

    $message = "Exception: " . $e->getMessage() . " (in " . $e->getFile() . " line " . $e->getLine() . ")";
    if( registry("config")->debug ) $message .= "\n\n" . $e->getTraceAsString();
    var_dump($message) ;
}