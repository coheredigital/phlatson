<?php



declare (strict_types = 1);

namespace Flatbed;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR);
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR);
define('CACHE_PATH', ROOT_PATH . "cache" . DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH . "Flatbed" . DIRECTORY_SEPARATOR);
define('ROOT_URL', "/");

// use composer autoloader
$composer = require_once(VENDOR_PATH . 'autoload.php');

// config ref
\ref::config('expLvl', 2);
\ref::config('maxDepth', 4);

$exceptionHandler = new ErrorHandler();

try {

    $request = new Request;

    $pages = new Pages();

    
    $file = new JsonObject("/site/pages/about/data.json");

    $page = new Page('/about/');
    r($page);
    r($file->get('title'));
    r($file->get('empty'));
    $file->set("hash", md5(date('U')));

    $file->save();
    r($request);

    // echo $router->execute();

} catch (Exceptions\FlatbedException $exception) {
    echo $exception->render($config);
}
