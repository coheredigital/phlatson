<?php

declare(strict_types=1);

namespace Phlatson;

// define a few system constants
const PHLATSON = 0001;

// use composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {
    $phlatson = new Phlatson(__DIR__, new Request());

    $phlatson->registerApp("site");
    $phlatson->registerApp("site-portfolio");

    $phlatson->execute();
} catch (\Exception $exception) {
    echo $exception;
}
