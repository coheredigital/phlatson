<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");


require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

$api = new App();

app('config', new Config);
app('request', new Request);

$rootRoute = new Route();
$rootRoute
    ->name("root")
    ->hostname(app("config")->hostname)
    ->path("/");

app('router', new Router($rootRoute) );

// setup config routes
foreach (app("config")->routes as $r){
    $route = new Route($r);
    app("router")->add($route);
}

app('extensions', new Extensions);
app('sanitizer', new Sanitizer);
app('pages', new Pages);
app('users', new Users);
app('fields', new Fields);
app('templates', new Templates);
app('session', new Session);

// execute the app

try {
    app('router')->run(app('request'));
} catch(Exception $e) {

    $message = "Exception: " . $e->getMessage() . " (in " . $e->getFile() . " line " . $e->getLine() . ")";
    if( app("config")->debug ) $message .= "\n\n" . $e->getTraceAsString();
    var_dump($message) ;
}