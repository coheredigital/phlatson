<?php


// require "{$config->paths->root}/libraries/ref/ref.php";

// ref::config('expLvl', 0);
// ref::config('validHtml', true);

$field = new Field;
$field->name = "test";
$field->fieldtype = "FieldtypeText";
$field->input = "InputText";

r($field);


$f = $fields->create([
	name => "summary",
	fieldtype => "FieldtypeText",
]);

r($f);

$title = $fields->get("title");
r($title);

