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

    $flatbed = new Flatbed\Flatbed;
    $flatbed->api('flatbed', $flatbed, true);
    $flatbed->api('profile', $profile, true);
    $flatbed->api('config', $config = new Flatbed\Config, true);
    $flatbed->api('request', $request = new Flatbed\Request, true);
    $flatbed->api('users', new Flatbed\Users, true);
    $flatbed->api('session', new Flatbed\Session($config->sessionName), true);
    // $flatbed->api('events', new Flatbed\Events, true);
    $flatbed->api('extensions', new Flatbed\Extensions, true);
    $flatbed->api('fields', new Flatbed\Fields, true);
    $flatbed->api('pages', $pages = new Flatbed\Pages, true);
    $flatbed->api('roles', new Flatbed\Roles, true);
    $flatbed->api('templates', new Flatbed\Templates, true);
    $flatbed->api('views', new Flatbed\Views, true);
    $flatbed->api('router', $router = new Flatbed\Router($request), true);

    echo $router->execute();  



} catch(FlatbedException $exception) {
    echo $exception->render($config);
}
