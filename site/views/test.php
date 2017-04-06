<?php

// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);


$pages->get('news');
$pages->get('about');
$pages->get('derp');

r($pages->path);
r($pages);