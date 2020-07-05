<?php

declare (strict_types = 1);

namespace Phlatson;

// define a few system constants
const PHLATSON = 0001;

define("ROOT_PATH", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__ . "/"));
define("DATA_PATH", ROOT_PATH . "site/");
define("TEMP_PATH", ROOT_PATH . "temp/");

// use composer autoloader
require_once(ROOT_PATH . 'vendor/autoload.php');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {

    $phlatson = new Phlatson();

    $finder = new Finder(__DIR__);
    $finder->addPathMapping("Page", "/site/pages/");
    $finder->addPathMapping("Template", "/site/templates/");
    $finder->addPathMapping("Field", "/site/fields/");
    $finder->addPathMapping("Config", '/site/config/');
    $finder->addPathMapping("Config", '/Phlatson/data/config/');
    // $finder->addPathMapping("Extension", '/site/extensions/');
    // $finder->addPathMapping("Extension", '/Phlatson/data/extensions/');
    $phlatson->api("finder", $finder);

    $request = new Request();
    
    $phlatson->api("request", $request);

    // determine the requested page
    $url = $request->url;
    $page = $finder->getType("Page",$url);
    $template = $page->template;
    $view = $template->view;

    $phlatson->api('request', $request);
    $phlatson->api('page', $page);
    $phlatson->api('template', $template);
    $phlatson->api('view', $view);

    if ($view instanceof View) {
        echo $view->render();
    }
    
} catch (\Exception $exception) {
    echo $exception;
}