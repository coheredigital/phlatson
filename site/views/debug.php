<?php

namespace Phlatson;

$folder = new Folder($app, 'pages');

dump($folder);
dump($folders = $folder->subfolders());
dump($folder->files());
dump($folder->parent());
dump($folder);
