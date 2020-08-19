<?php

namespace Phlatson;

$folder = new Folder($app, 'pages');

dump($page);
dump($page->rootFolder());
// dump($page->parent());
dump($page->url());

// dump($folder->files());
// dump($folder->parent());
// dump($folder->isRoot());
// dump($subfolder = $folder->children()->first());
// dump($subfolder->rootParent());
// dump($subfolder->isRoot());
// dump($subfolder->hasFiles());
