<?php

// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);

$news = $pages->get("/about")->children()->limit(2);

r($news);

ref::config('expLvl', 0);
foreach ($news as $p) {
    r($p);
}
