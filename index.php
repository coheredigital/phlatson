<?php

declare(strict_types=1);

namespace Phlatson;

// define a few system constants
const PHLATSON = 0001;

define("ROOT_PATH", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__ . "/"));

// use composer autoloader
require_once(ROOT_PATH . 'vendor/autoload.php');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

\Kint\Renderer\RichRenderer::$theme = 'aante-light.css';

try {

    $phlatson = new Phlatson(ROOT_PATH . "site");
    $phlatson->api('phlatson', $phlatson);

    // TODO: move this to separate init file / or addon
    // $clockwork = \Clockwork\Support\Vanilla\Clockwork::init([
    //     'api' => '/__clockwork/?request=',
    //     'storage_files_path' => __DIR__ . "/storage/clockwork/"
    // ]);
    // $phlatson->api('clockwork', $clockwork);

    $finder = new Finder(__DIR__);
    $finder->addPathMapping("Page", ROOT_PATH . "site/pages/");

    $finder->addPathMapping("Template", ROOT_PATH . "Phlatson/data/templates/");
    $finder->addPathMapping("Template", ROOT_PATH . "site/templates/");

    $finder->addPathMapping("Field", ROOT_PATH . "Phlatson/data/fields/");
    $finder->addPathMapping("Field", ROOT_PATH . "site/fields/");

    $finder->addPathMapping("Fieldtype", ROOT_PATH . "site/fieldtypes/");
    $finder->addPathMapping("Fieldtype", ROOT_PATH . "Phlatson/data/fieldtypes/");

    // config files
    $finder->addPathMapping("Config", ROOT_PATH . "Phlatson/data/config/");
    $finder->addPathMapping("Config", ROOT_PATH . "site/config/");
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

    // $clockwork->requestProcessed();
} catch (\Exception $exception) {
    echo $exception;
}
