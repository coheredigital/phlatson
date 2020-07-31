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

    $request = new Request();
    $phlatson = new Phlatson();


    $phlatson->importApp(new App(ROOT_PATH . "site"));
    // $phlatson->importApp(new App(ROOT_PATH . "site-portfolio"));

    $phlatson->execute($request);

    // $clockwork->requestProcessed();
} catch (\Exception $exception) {
    echo $exception;
}
