<?php



// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);

$input = $extensions->get("InputTinymce");

r($input->file);
r($input->getAttributes());
r($input->getPath());
r($input);
