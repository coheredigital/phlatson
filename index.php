<?php

define("XPAGES", true);
define('ROOT_PATH', normalizePath(dirname(__FILE__)));

require_once ROOT_PATH . 'system/_autoload.php';
require_once ROOT_PATH . 'system/_functions.php';



/* instatiate api variables */
api::register('router', new Router());
api::register('config', new Config);
api::register('sanitizer', new Sanitizer);
api::register('input', new Input);
api::register('pages', new Pages);
api::register('users', new Users);
api::register('fields', new Fields);
api::register('templates', new Templates);
api::register('extensions', new Extensions);
api::register('session', new Session);


Router::execute();
extract( api::get() );

if( $page instanceof AdminPage) {
    $layoutFile = api::get('config')->paths->root . "admin.php";
}
else{
    $template = $page->template;
    $layoutFile = $template->layout;
}




include $layoutFile;


