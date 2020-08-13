<?php

namespace Phlatson;

$repeat = 1000;

$executionStartTime = \microtime(true);

dump($app);
dump($page);

for ($i = 0; $i < $repeat; $i++) {
    // code...
    $folder = new Folder($app, 'pages');

    // $content = $folder->contents();
    $folders = $folder->children();
    $files = $folder->files();

    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
    $files = $folder->file('data.json');
}
dump($folder);

$executionEndTime = microtime(true);

//The result will be in seconds and milliseconds.
$seconds = $executionEndTime - $executionStartTime;
dump($seconds);
