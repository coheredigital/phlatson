<?php

namespace Phlatson;

$folder = new DataFolder('fields', $app);
$data = $folder->get('title');

dump($data);
dump($data->path());
dump($data->folder());

// dump($app);
// dump($app->config());
// dump($app->config()->path());
// dump($folder);
// dump($folder->get('title'));
// dump($folder->get('title')->path());
// dump($folder->get('title')->folder());

dump($page);
dump($page->path());
dump($page->folder());
dump($page->url());
// dump($page->template());
// dump($page->parent());
// dump($this);
