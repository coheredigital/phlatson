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

$folders = glob($folder . "*",  GLOB_ONLYDIR | GLOB_NOSORT)

?>

<div>
    <?php
    foreach ($folders as $folder) {
        $file =  "$folder\data.json";

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $json = json_decode($json);
            $data[] = $json;
        }
    }

    ?>

</div>
<div class="list">
    <?php foreach ($data as $value): ?>
        <?= $value->title ?? $value['title'] ?>
        <hr>
    <?php endforeach ?>
</div>

<?php

$end = microtime(true);
$creationtime = round(($end - $start), 2);
echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";
