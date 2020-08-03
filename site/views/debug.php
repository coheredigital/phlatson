<?php

namespace Phlatson;

$folder = new DataFolder();
$folder->addPath('C:/Users/Adam/Websites/phlatson/Phlatson/data/fields');
$folder->addPath('C:/Users/Adam/Websites/phlatson/site/fields');

$data = $folder->get('title');
$data = $folder->get('markdown');
$data = $folder->get('content');

dump($folder);
dump($app);
dump($page);
dump($page->template());
dump($page->parent());
dump($this);
