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

    $flatbed->register('config', new Config, true);
    $flatbed->register('request', $request = new Request, true);
    
    $flatbed->register('events', new Events, true);

    $flatbed->register('extensions', new Extensions, true);
    $flatbed->register('fields', new Fields, true);

    $flatbed->register('pages', $pages = new Pages, true);
    $flatbed->register('users', new Users, true);
    $flatbed->register('roles', new Roles, true);

    $flatbed->register('templates', new Templates, true);
    $flatbed->register('views', new Views, true);

    $flatbed->register('session', new Session, true);
    $flatbed->register('router', $router = new Router($request), true);

    echo $router->execute();  

    // end performance tracking
    $end = microtime(true);
    $creationtime = round(($end - $start), 2);
    echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";

} catch(FlatbedException $exception) {
    echo $exception->render($flatbed("config"));
}
