<?php

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

$api = new Api();

api('config', new Config);

api('request', new Request);
api('router', new Router(api("config")->hostname) );

// setup config routes
foreach (api("config")->routes as $r){
    $route = new Route($r);
    api("router")->add($route);
}

api('extensions', new Extensions);
api('sanitizer', new Sanitizer);
api('pages', new Pages);
api('users', new Users);
api('fields', new Fields);
api('templates', new Templates);
api('session', new Session);

// execute the app

try {
    api('router')->run(api('request'));
} catch(Exception $e) {

    $message = "Exception: " . $e->getMessage() . " (in " . $e->getFile() . " line " . $e->getLine() . ")";
    if( api("config")->debug ) $message .= "\n\n" . $e->getTraceAsString();
    echo $message;
}
