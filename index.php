<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';
require_once ROOT_PATH . 'system/_interfaces.php';
require_once ROOT_PATH . 'system/_traits.php';



/* instatiate api variables */
app('config', new Config);
app('request', new Request);

// set default request behaviour
$pagesRoute = new Route();
$pagesRoute
    ->path(":all")
    ->callback("Pages.render");

app('router', new Router($pagesRoute));

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
app('logger', new Logger);
app('events', new Events);

$app = app();



// execute the app
try {
    app('router')->run(app('request'));
} catch(Exception $e) {

    $message = "Exception: " . $e->getMessage() . " (in " . $e->getFile() . " line " . $e->getLine() . ")";
    if( app("config")->debug ) $message .= "\n\n" . $e->getTraceAsString();
    var_dump($message) ;
}