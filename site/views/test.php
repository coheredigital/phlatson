<?php

// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 2);
ref::config('validHtml', true);

$input = $extensions->get("InputTinymce");

r($input->file);
r($input->path);
r($input->getPath());
r($input->url);
?>
<hr>
<hr>
<hr>
<hr>
<?php 
r($page->file);
r($page->parent);
r($page->path);
r($page->getPath());
r($page->url);
?>
<hr>
<hr>
<hr>
<hr>
<?php 

$field = $fields->get('title');
r($field->file);
r($field->path);
r($field->getPath());
r($field->url);




