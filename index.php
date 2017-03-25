<?php

declare(strict_types=1);

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
    // add pages route
    $flatbed->api('router')->add(new Route([
        "path" =>":all",
        "callback" => "Pages.render"
    ]));


    /* setup config routes, default is just the admin route */
    if(count($flatbed->api("config")->routes)) foreach ($flatbed->api("config")->routes as $r){
        $flatbed->api("router")->add(new Route($r));
    }

    $flatbed->api('events', 'Events', true);

    $flatbed->api('fields', new Fields, true);
    $flatbed->api('extensions', new Extensions, true);

    $flatbed->api('pages', 'Pages', true);
    $flatbed->api('users', 'Users', true);
    $flatbed->api('roles', 'Roles', true);

    $flatbed->api('templates', 'Templates', true);
    $flatbed->api('views', 'Views', true);

    $flatbed->api('session', new Session, true);
    $flatbed->api('logger', 'Logger', true);



    // run the app
    $flatbed->api('router')->run($request);

} catch(FlatbedException $exception) {
    echo $exception->render(Api::get("config"));
}