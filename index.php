<?php

define("FLATBED", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';
require_once ROOT_PATH . 'system/_interfaces.php';
require_once ROOT_PATH . 'system/_traits.php';

try {

    /* instantiate app variables */
    app('config', new Config);
    app('request', $request = new Request);

    /* init Router and set default request behaviour */
    $pagesRoute = new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]);
    app('router', new Router);

    /* setup config routes, default is just the admin route */
    if(count(app("config")->routes)) foreach (app("config")->routes as $r){
        $route = new Route($r);
        app("router")->add($route);
    }


    // wrapped in try while extensions requires instantiation
    app('events', 'Events');
    app('extensions', new Extensions);
    app('pages', 'Pages');
    app('users', 'Users');
    app('fields', 'Fields');
    app('templates', 'Templates');
    app('session', 'Session');
    app('logger', 'Logger');

    // add pages route last just before running app
    $pagesRoute = new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]);
    app('router')->add($pagesRoute);

    // run the app
    app('router')->run($request);

} catch(FlatbedException $exception) {
    echo $exception->render();
}