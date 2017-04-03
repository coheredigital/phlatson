<?php

require 'C:\Users\Adam\Websites\dev\flatbed\libraries\ref\ref.php';

function getMemoryUse() : string
{
    $memory = memory_get_usage() / pow( 1024 , 2 );
    $memory = round( $memory , 2);
    return "{$memory}mb";
}


$start = microtime(true);

$folder = 'C:\Users\Adam\Websites\dev\flatbed\site\pages\news\\';

$data = [];


$subfolders = glob( $folder. "*", GLOB_ONLYDIR);
// r($subfolders);

foreach ($subfolders as $folder) {

    $file = $folder . "\data.json";

    if (file_exists($file)) {

        $json = file_get_contents($file);
        $json = json_decode($json);
        $data[] = $json;
    }

}



r($data);








$end = microtime(true);
$creationtime = round(($end - $start), 2);
echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";
