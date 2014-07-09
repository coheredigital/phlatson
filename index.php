<?php

require_once 'system/core/_autoload.php';
require_once 'system/core/_functions.php';
require_once 'system/core/_interfaces.php';

define("XPAGES", true);
define('ROOT_PATH', normalizePath(dirname(__FILE__)));

// instatiate api variables
api::register('config', new Config, true);
api::register('sanitizer', new Sanitizer(), true);
api::register('input', new Input(), true);
api::register('users', new Users(), true);
api::register('session', new Session(), true);
api::register('extensions', new Extensions(), true);
api::register('fields', new Fields(), true);
api::register('templates', new Templates(), true);
api::register('pages', new Pages(), true);


// execute core init GET THIS PARTY STARTED!!!
//try {

    // loop through api registry and assign variable for easy use in layout files
    // $page->get('/some-page') instead of api::get('pages')->get('/some-page') YUCK!  :)

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

//} catch (Exception $e) {
//    echo 'Caught exception: ', $e->getMessage(), "\n";
//}

