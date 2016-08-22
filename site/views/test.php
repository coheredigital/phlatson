<?php

echo "Field new API";
echo "<br>";

$field = new Field;
$field->name = "test";
$field->fieldtype = "FieldtypeText";
$field->input = "InputText";

var_dump($field->rootPath);
var_dump($field->name);
var_dump($field->path);

$field->save();
// 
echo "<br>";
echo "<br>";
echo '=====================================================';

$f = $fields->create("summary");
var_dump($f->name);
var_dump($f->className);

echo "<br>";
echo "<br>";
echo '=====================================================';
echo "<br>";
echo "<br>";
$title = $fields->get("title");
var_dump($title->rootPath);
var_dump($title->path);
var_dump($title->file);
var_dump($title->name);

