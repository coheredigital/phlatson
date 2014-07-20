<?php

define("XPAGES", true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

api('input', new Input);
api('router', $router = new Router());
api('config', new Config);
api('extensions', new Extensions);
api('sanitizer', new Sanitizer);
api('pages', new Pages);
api('users', new Users);
api('fields', new Fields);
api('templates', new Templates);
api('session', new Session);

Router::add("fields", "/fields/:name", function(){
        $field = api("fields")->get($name);
        var_dump($field);
    });

// execute the app
Router::execute();