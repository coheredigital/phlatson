<?php

namespace Phlatson;

$folder = new Folder($app, 'pages');

dump($page);
dump($folder);
dump($folders = $folder->subfolders());
dump($folder->files());
dump($folder->parent());
dump($folder->isRoot());
dump($subfolder = $folder->subfolders()->first());
dump($subfolder->rootParent());
dump($subfolder->isRoot());
dump($subfolder->hasFiles());
