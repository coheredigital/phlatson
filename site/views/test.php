<?php


// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);

$field = $fields->get('input');



r($field->getDirectory());
r($field->getRootPath());

r($field->isSystem());
r($field);
