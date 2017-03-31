<?php

// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 0);
ref::config('validHtml', true);

$input = $extensions->get("InputTinymce");

r($input->file);
r($input->path);
r($input->getPath());
r($input);



r($page->file);
r($page->path);
r($page->getPath());
r($page->url);


$admin = $extensions->get("FieldtypeText");

r($admin->file);
r($admin->path);
r($admin->getPath());
r($admin);
