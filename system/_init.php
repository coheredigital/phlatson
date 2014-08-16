<?php

require_once ROOT_PATH . 'system/_functions.php';
require_once ROOT_PATH . 'system/_autoload.php';

/* instatiate api variables */

$api = new Api();

api('config', new Config);
api('request', new Request);
api('router', new Router);
api('extensions', new Extensions);
api('sanitizer', new Sanitizer);
api('pages', new Pages);
api('users', new Users);
api('fields', new Fields);
api('templates', new Templates);
api('session', new Session);


$pagesRoute = new Route;
$pagesRoute->path(":all");
$pagesRoute->callback(
    function ($url = null) {
        $api = api();
        $page = api("pages")->get($url);
        if ($page instanceof Page) {
            extract(api()); // get access to api variables for rendered layout
            include $page->template->layout;
        }
    }
);


api('router')->add($pagesRoute);

api('router')->run(api('request'));