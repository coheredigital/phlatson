<?php

namespace Phlatson;

// use composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

$repeat = 1000;

$executionStartTime = \microtime(true);

$phlatson = new Phlatson(__DIR__, new Request());
$phlatson->registerApp('site');
$app = $phlatson->app();

dump($app);

for ($i = 0; $i < $repeat; $i++) {
    // code...

    $folder = new Folder($app, 'pages');

    $subfolders = $folder->children();
    $files = $folder->subfolders();
    $files = $folder->files();
}

$executionEndTime = microtime(true);

//The result will be in seconds and milliseconds.
$seconds = $executionEndTime - $executionStartTime;
dump($seconds);
