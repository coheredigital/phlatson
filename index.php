<?php

declare(strict_types=1);


$profile = new stdClass;
/* instantiate app variables */
$profile->start = microtime(true);



define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR );

require_once SYSTEM_PATH . 'init.php';


try {

    $flatbed = new Flatbed;
    $flatbed->api('profile', $profile, true);
    $flatbed->api('config', $config = new Config, true);
    $flatbed->api('request', $request = new Request, true);
    $flatbed->api('users', new Users, true);
    $flatbed->api('session', new Session($config->sessionName), true);
    // $flatbed->api('events', new Events, true);
    $flatbed->api('extensions', new Extensions, true);
    $flatbed->api('fields', new Fields, true);
    $flatbed->api('pages', $pages = new Pages, true);
    $flatbed->api('roles', new Roles, true);
    $flatbed->api('templates', new Templates, true);
    $flatbed->api('views', new Views, true);
    $flatbed->api('router', $router = new Router($request), true);

    echo $router->execute();  



} catch(FlatbedException $exception) {
    echo $exception->render($config);
}
