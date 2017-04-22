<?php

declare(strict_types=1);


$profile = new stdClass;
/* instantiate app variables */
$profile->start = microtime(true);


define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR );
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR );
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR );
define('CACHE_PATH', ROOT_PATH . "cache" . DIRECTORY_SEPARATOR );
define('CORE_PATH', SYSTEM_PATH . "core" . DIRECTORY_SEPARATOR );
define('ROOT_URL', "/");

require_once CORE_PATH . '_functions.php';

// check for composer autoloader
$composerAutoloader = VENDOR_PATH .'autoload.php'; // composer autoloader
if(file_exists($composerAutoloader)) require_once($composerAutoloader);
else require_once CORE_PATH . 'FlatbedAutoloader.php';
require_once CORE_PATH . '_interfaces.php';

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
