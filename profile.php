<?php

require 'C:\Users\Adam\Websites\dev\flatbed\libraries\ref\ref.php';

// add ref for debugging, remove later
ref::config('expLvl', 0);
ref::config('validHtml', true);



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


$it = new FilesystemIterator( $folder );



?>

<div class="">
    <?php
    foreach ($it as $folder) {
        $file =  "$folder\data.json";

        echo $folder->getBasename() . "<br>";
        echo $folder->getBasename() . "<br>";
        echo "<hr>";

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $json = json_decode($json);
            $data[] = $json;
        }
    }

    ?>

</div>




<?php

$end = microtime(true);
$creationtime = round(($end - $start), 2);
echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";