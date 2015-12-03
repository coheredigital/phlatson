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

    $flatbed = new App;

    $flatbed->api('config', $config = new Config);
    $flatbed->api('request', $request = new Request);

    /* init Router and set default request behaviour */
    $pagesRoute = new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]);
    $flatbed->api('router', new Router);

    /* setup config routes, default is just the admin route */
    if(count($flatbed->api("config")->routes)) foreach ($flatbed->api("config")->routes as $r){
        $route = new Route($r);
        $flatbed->api("router")->add($route);
    }


    // wrapped in try while extensions requires instantiation
    $flatbed->api('events', 'Events');
    $flatbed->api('extensions', new Extensions);
    $flatbed->api('pages', 'Pages');
    $flatbed->api('users', 'Users');
    $flatbed->api('fields', 'Fields');
    $flatbed->api('templates', 'Templates');
    $flatbed->api('session', 'Session');
    $flatbed->api('logger', 'Logger');

    // add pages route last just before running app
    $pagesRoute = new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]);
    $flatbed->api('router')->add($pagesRoute);

    // run the app
    $flatbed->api('router')->run($request);

} catch(FlatbedException $exception) {
    echo $exception->render($config);
}