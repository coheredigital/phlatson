<?php

define("XPAGES", true);
define('ROOT_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);


require_once 'system/core/_autoload.php';
require_once 'system/core/_functions.php';
require_once 'system/core/_interfaces.php';


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

    $layout = $page->layout;
    if (is_file($layout)) include $layout;

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

