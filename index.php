<?php

require_once 'system/_autoload.php';
require_once 'system/_functions.php';


define("XPAGES", true);
define('ROOT_PATH', normalizePath(dirname(__FILE__)));

/* instatiate api variables */
api::register('config', new Config);

api::register('sanitizer', new Sanitizer);
api::register('input', new Input);
api::register('pages', new Pages);
api::register('users', new Users);
api::register('fields', new Fields);
api::register('templates', new Templates);
api::register('extensions', new Extensions);

api::register('session', new Session);


extract( api::get() );



$page = $pages->get( $input->url );
if(!$page instanceof Page){
    throw new Exception("No valid page found (404?)");
}

if( $page instanceof AdminPage) {
    $layoutFile = api::get('config')->paths->root . "admin.php";
}
else{
    $template = $page->template;
    $layoutFile = $template->layout;
}

include $layoutFile;


