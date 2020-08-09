<?php

namespace Phlatson;

$folder = new DataFolder($app, 'pages');

dump($folder);
dump($folders = $folder->children());
dump($folder->files());
dump($folder->parent());
