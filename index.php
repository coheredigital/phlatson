<?php


declare(strict_types=1);

define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR );
define('VENDOR_PATH', ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR );
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR );
define('CACHE_PATH', ROOT_PATH . "cache" . DIRECTORY_SEPARATOR );
define('CORE_PATH', ROOT_PATH . "Flatbed" . DIRECTORY_SEPARATOR );
define('ROOT_URL', "/");

// pre includes some default core file for flatbed
// files / class that will be REQUIRED for every single request

require CORE_PATH . '_functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// use composer autoloader
$composer = require_once(VENDOR_PATH .'autoload.php');

// config ref
ref::config('expLvl', 1);
ref::config('maxDepth', 2);

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

} catch(Exceptions\FlatbedException $exception) {
    echo $exception->render($config);
}
