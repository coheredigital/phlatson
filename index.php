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
app('events', 'Events');
app('extensions', new Extensions);
//app('sanitizer', 'Sanitizer');
app('pages', 'Pages');
app('users', 'Users');
app('fields', 'Fields');
app('templates', 'Templates');
app('session', 'Session');
app('logger', 'Logger');


try {
    app('router')->run(app('request'));
} catch(FlatbedException $exception) {
//    $message = "Exception: " . $exception->getMessage() . "\n(in " . $exception->getFile() . " line " . $exception->getLine() . ")";
//    if( app("config")->debug )
//        $message .= "\n\n" . $exception->getTraceAsString();
    echo $exception->render();

}