<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

api('input', new Input);
api('router', new Router());
api('config', new Config);
api('extensions', new Extensions);
api('sanitizer', new Sanitizer);
api('pages', new Pages);
api('users', new Users);
api('fields', new Fields);
api('templates', new Templates);
api('session', new Session);

//Router::add("pages", "/:url", function($url = null){
//        $page = api("pages")->get($url);
//        if( $page instanceof Page ) {
//            extract( api::get() ); // get access to api variables for rendered layout
//            include $page->template->layout;
//        }
//    });

Router::add("fields", "/fields/<name>", function($name = null){
        $field = api("fields")->get($name);
        var_dump($field);
    });

Router::add("templates", "/templates/:name", function($name = null){
        $field = api("templates")->get($name);
        var_dump($field);
    });

// execute the app
Router::execute();