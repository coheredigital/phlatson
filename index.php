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

    $app = new App(ROOT_PATH . "site");
    $app->api('phlatson', $app);

    // TODO: move this to separate init file / or addon
    // $clockwork = \Clockwork\Support\Vanilla\Clockwork::init([
    //     'api' => '/__clockwork/?request=',
    //     'storage_files_path' => __DIR__ . "/storage/clockwork/"
    // ]);
    // $app->api('clockwork', $clockwork);

    $request = new Request();
    $app->api("request", $request);

    // determine the requested page
    $url = $request->url;
    $page = $app->api('finder')->get("Page", $url);
    if (!$page) {
        // TODO: tidy this
        $page = $finder->get("Page", "/404");
    }
    $app->api('page', $page);


    $view = $page->template()->view();

    if ($view instanceof View) {
        echo $view->render();
    }

    // $clockwork->requestProcessed();
} catch (\Exception $exception) {
    echo $exception;
}
