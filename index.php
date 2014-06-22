<?php

require_once 'system/core/_autoload.php';
require_once 'system/core/_functions.php';
require_once 'system/core/_interfaces.php';

define("XPAGES", true);
define('ROOT_PATH', normalizePath(dirname(__FILE__)));


$config = new Config;

// execute core init GET THIS PARTY STARTED!!!
//try {

    Core::init($config);

    foreach (Core::api() as $name => $object) {
        if ($name !== "config" && $name !== "page" ) { // skip $config, it is already set
            ${$name} = $object;
        }
    }

    $page = $pages->get( $input->url );
    if(!$page instanceof Page){
        throw new Exception("No valid page found (404?)");
    }

    if( $page instanceof AdminPage) {
        $layoutFile = api('config')->paths->system . "index.php";
    }
    else{
        $layoutFile = $page->template->layout;
    }

    include $layoutFile;

//} catch (Exception $e) {
//    echo 'Caught exception: ', $e->getMessage(), "\n";
//}

