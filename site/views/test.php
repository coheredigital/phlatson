<?php


// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 2);
ref::config('validHtml', true);

$data = file_get_contents('C:\Users\Adam\Websites\dev\flatbed\MOCK_DATA_500.json');
$data = json_decode($data);

$limit = 10000;

$count = 0;


$pages->get('about');
echo "<div>";
while ($count < $limit) {
    
    echo $page->get('name');

    echo "<br>";
    $count++;
}
echo "</div>";