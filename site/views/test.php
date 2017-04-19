<?php


// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);

$data = file_get_contents('C:\Users\Adam\Websites\dev\flatbed\MOCK_DATA_500.json');
$data = json_decode($data);

$limit = 10000;

$count = 0;


r($request);
r($response);
