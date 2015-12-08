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

    $flatbed = new Flatbed;

    $flatbed->api('config', new Config, true);
    $flatbed->api('request', $request = new Request, true);

    /* init Router and set default request behaviour */
    $pagesRoute = new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]);

    $flatbed->api('router', new Router, true);

    /* setup config routes, default is just the admin route */
    if(count($flatbed->api("config")->routes)) foreach ($flatbed->api("config")->routes as $r){
        $route = new Route($r);
        $flatbed->api("router")->add($route);
    }


    $flatbed->api('events', 'Events', true);
    $flatbed->api('extensions', new Extensions, true);
    $flatbed->api('pages', 'Pages', true);
    $flatbed->api('users', 'Users', true);
    $flatbed->api('fields', 'Fields', true);
    $flatbed->api('templates', 'Templates', true);
    $flatbed->api('session', new Session, true);
    $flatbed->api('logger', 'Logger', true);

    // add pages route last just before running app
    $flatbed->api('router')->add(new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]));

    // run the app
    $flatbed->api('router')->run($request);

} catch(FlatbedException $exception) {
    echo $exception->render(Api::get("config"));
}