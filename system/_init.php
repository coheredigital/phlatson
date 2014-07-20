<?php

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

api('request', new Request);
api('router', new Router);
api('config', new Config);
api('extensions', new Extensions);
api('sanitizer', new Sanitizer);
api('pages', new Pages);
api('users', new Users);
api('fields', new Fields);
api('templates', new Templates);
api('session', new Session);

Router::get("/(:all)", function($url = null){
        $page = api("pages")->get($url);
        if( $page instanceof Page ) {
            extract( api::get() ); // get access to api variables for rendered layout
            include $page->template->layout;
        }
    });

Router::dispatch();