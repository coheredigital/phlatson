<?php

include '_autoload.php';
include '_functions.php';
include '_interfaces.php';


$config = new Config;

// execute core init GET THIS PARTY STARTED!!!
try {

    Core::init($config);

    foreach (Core::api() as $name => $object) {
        if ($name !== "config" && $name !== "page" ) { // skip $config, it is already set
            ${$name} = $object;
        }
    }

    $page = api("pages")->get( api("input")->url );
    if(!$page instanceof Page){
        throw new Exception("No valid page found (404?)");
    }

    $t = $page->template;
    $l = $t->layout;
    if (is_file($l)) include $l;

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

