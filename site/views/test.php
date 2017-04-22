<?php


// add ref for debugging, remove later
ref::config('expLvl', 0);
ref::config('validHtml', true);

$data = file_get_contents('C:\Users\Adam\Websites\dev\flatbed\MOCK_DATA_500.json');
$data = json_decode($data);

$limit = 10000;

$count = 0;

$extension = $extensions->get('FieldtypeText');

$matches = preg_split("/(?=[A-Z])/", 'MarkupPagetree', 0, PREG_SPLIT_NO_EMPTY );


r($matches);
r($extension);
