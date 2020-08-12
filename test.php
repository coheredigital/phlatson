<?php

declare(strict_types=1);

namespace Phlatson;

// use composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

$phlatson = new Phlatson(__DIR__, new Request());

$phlatson->registerApp('site');
$phlatson->registerApp('site-portfolio');

// get the current app
$app = $phlatson->app();

$folder = new Folder($app, '/pages/');

dump($app);
dump($folder);
dump($folder->index());
dump($folder->children());
dump($folder->children()->get('benchmark'));
