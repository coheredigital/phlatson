<?php

namespace Phlatson;

$folder = new DataFolder("C:\Users\Adam\Websites\phlatson\site\pages");

dump($folder);
dump($folder->subfolders());
dump($folder->files());


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
dump($app);
