<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

$api = new api();

$api::register('input', new Input);
$api::register('router', new Router());
$api::register('config', new Config);
$api::register('extensions', new Extensions);
$api::register('sanitizer', new Sanitizer);

$api::register('pages', new Pages);
$api::register('users', new Users);
$api::register('fields', new Fields);
$api::register('templates', new Templates);

$api::register('session', new Session);

// execute the app
Router::execute();