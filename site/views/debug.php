<?php

namespace Phlatson;

$folder = new DataFolder('fields', $app);

$data = $folder->get('title');
$data = $folder->get('markdown');
$data = $folder->get('content');


dump($app);
dump($app->config());
dump($app->config()->path());
dump($folder);
dump($folder->get('title'));
// dump($folder->get('title')->path());
// dump($folder->get('title')->folder());

// dump($page);
// dump($page->template());
// dump($page->parent());
// dump($this);
