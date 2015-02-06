<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");


require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';
require_once ROOT_PATH . 'system/_interfaces.php';
require_once ROOT_PATH . 'system/_traits.php';

/* instatiate api variables */

$api = new App();

app('config', new Config);
app('request', new Request);


app('router', new Router());


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


app("events")->listen("after.Page.save",function($event){
    app("logger")->add("changelog","We saved this page: {$event->object->url}");
});


// set default request behaviour
$pagesRoute = new Route();
$pagesRoute
    ->path(":all")
    ->callback("Pages:render");
app('router')->defaultRoute = $pagesRoute;

// execute the app
try {
    app('router')->run(app('request'));
} catch(Exception $e) {

    $message = "Exception: " . $e->getMessage() . " (in " . $e->getFile() . " line " . $e->getLine() . ")";
    if( app("config")->debug ) $message .= "\n\n" . $e->getTraceAsString();
    var_dump($message) ;
}