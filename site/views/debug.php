<?php

namespace Phlatson;

$folder = new Folder($app, 'pages');

dump($page);
dump($finder);
dump($child = $app->getPage('/about/contact-us'));
dump($folder);
dump($folder->contents());
dump($folders = $folder->find('/about/contact-us/'));

dump($folder);
// dump($folder->files());
// dump($folder->parent());
// dump($folder->isRoot());
// dump($subfolder = $folder->children()->first());
// dump($subfolder->rootParent());
// dump($subfolder->isRoot());
// dump($subfolder->hasFiles());
