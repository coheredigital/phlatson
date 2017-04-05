<?php

declare(strict_types=1);

/* instantiate app variables */
$start = microtime(true);


define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR );
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR );
define('CORE_PATH', SYSTEM_PATH . "core" . DIRECTORY_SEPARATOR );
define('ROOT_URL', "/");

require_once CORE_PATH . '_functions.php';
require_once CORE_PATH . '_autoload.php';
require_once CORE_PATH . '_interfaces.php';

try {

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
    if( count( $flatbed->api("config")->routes ) ) {

        foreach ($flatbed->api("config")->routes as $r){
            $flatbed->api("router")->add(new Route($r));
        }

    }

    $flatbed->api('events', 'Events', true);


    $flatbed->api('extensions', new Extensions, true);
    $flatbed->api('fields', new Fields, true);

    $flatbed->api('pages', 'Pages', true);
    $flatbed->api('users', 'Users', true);
    $flatbed->api('roles', 'Roles', true);

    $flatbed->api('templates', 'Templates', true);
    $flatbed->api('views', 'Views', true);

    $flatbed->api('session', new Session, true);
    $flatbed->api('logger', 'Logger', true);
    
    // run the app
    $flatbed->api('router')->run($request);

    // end performance tracking
    $end = microtime(true);
    $creationtime = round(($end - $start), 2);
    echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";

} catch(FlatbedException $exception) {
    echo $exception->render(Api::get("config"));
}
