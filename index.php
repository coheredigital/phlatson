<?php

require_once 'system/core/_autoload.php';
require_once 'system/core/_functions.php';
require_once 'system/core/_interfaces.php';

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

//try {

    /*
     * loop through api registry and assign variable for easy use in layout files
     * $page->get('/some-page') instead of api::get('pages')->get('/some-page') YUCK!  :)
     * */
    foreach (api::get() as $name => $object) {
        ${$name} = $object;
    }

    $page = $pages->get( $input->url );
    if(!$page instanceof Page){
        throw new Exception("No valid page found (404?)");
    }

    if( $page instanceof AdminPage) {
        $layoutFile = api::get('config')->paths->system . "index.php";
    }
    else{
        $template = $page->template;
        $layoutFile = $template->layout;
    }

    include $layoutFile;
//
//} catch (Exception $e) {
//    echo 'Caught exception: ', $e->getMessage(), "\n";
//}

