<?php


// add ref for debugging, remove later
ref::config('expLvl', 0);
ref::config('validHtml', true);

$data = file_get_contents('C:\Users\Adam\Websites\dev\flatbed\MOCK_DATA_500.json');
$data = json_decode($data);

$limit = 10000;

$count = 0;

$collection = new ObjectCollection();

$collection->append($fields->get('content'));
$collection->append($fields->get('markdown'));



foreach ($collection as $field) {
    r($field);
    r($field->file);
    r($field->template);
    r($field->data('fieldtype'));
    r($field->get('fieldtype'));

}
