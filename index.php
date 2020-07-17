<?php

declare(strict_types=1);

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

\Kint\Renderer\RichRenderer::$theme = 'aante-light.css';

try {

    $phlatson = new Phlatson();

    // TODO: move this to seperate init file / or addon
    $clockwork = \Clockwork\Support\Vanilla\Clockwork::init([
        'api' => '/__clockwork/?request=',
        'storage_files_path' => __DIR__ . "/storage/clockwork/"
    ]);
    $phlatson->api('clockwork', $clockwork);

    $finder = new Finder(__DIR__);
    $finder->addPathMapping("Page", "/site/pages/");
    $finder->addPathMapping("Template", "/Phlatson/data/templates/");
    $finder->addPathMapping("Template", "/site/templates/");
    $finder->addPathMapping("Field", "/site/fields/");
    $finder->addPathMapping("Field", "/Phlatson/data/fields/");

    // config files
    $finder->addPathMapping("Config", '/Phlatson/data/config/');
    $finder->addPathMapping("Config", '/site/config/');
    $phlatson->api("finder", $finder);

    $config = $finder->get("Config", "site");
    $phlatson->api("config", $config);

    $request = new Request();
    $phlatson->api("request", $request);

    // determine the requested page
    $url = $request->url;
    $page = $finder->get("Page", $url);
    if (!$page) {
        // TODO: tidy this
        $page = $finder->get("Page", "/404");
    }
    $phlatson->api('request', $request);
    $phlatson->api('page', $page);
    

    $view = $page->template()->view();

    if ($view instanceof View) {
        echo $view->render();
    }

    $clockwork->requestProcessed();
} catch (\Exception $exception) {
    echo $exception;
}
