<?php


define("JPAGES", true);

define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/XPages');
define('CONTENT_URL', SITE_URL.'/site/content');
define('ADMIN_URL', SITE_URL.'/admin');

define('ROOT_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH.'system'. DIRECTORY_SEPARATOR);
define('CORE_PATH', SYSTEM_PATH . "core" . DIRECTORY_SEPARATOR);
define('ADMIN_PATH', SYSTEM_PATH . "admin". DIRECTORY_SEPARATOR);
define('SITE_PATH', ROOT_PATH . "site". DIRECTORY_SEPARATOR);
define('LAYOUTS_PATH', SITE_PATH . "layouts". DIRECTORY_SEPARATOR);
define('FIELDS_PATH', SITE_PATH . "fields". DIRECTORY_SEPARATOR);
define('TEMPLATES_PATH', SITE_PATH . "templates". DIRECTORY_SEPARATOR);
define('CONTENT_PATH', SITE_PATH . "content".DIRECTORY_SEPARATOR);

require_once( CORE_PATH . "_autoload.php");