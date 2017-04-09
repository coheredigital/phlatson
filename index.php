<?php

declare(strict_types=1);

/* instantiate app variables */
$start = microtime(true);


define("FLATBED", true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', ROOT_PATH . "system" . DIRECTORY_SEPARATOR );
define('SITE_PATH', ROOT_PATH . "site" . DIRECTORY_SEPARATOR );
define('CORE_PATH', SYSTEM_PATH . "core" . DIRECTORY_SEPARATOR );
define('ROOT_URL', "/");

require_once CORE_PATH . '_functions.php';
require_once CORE_PATH . '_autoload.php';
require_once CORE_PATH . '_interfaces.php';

try {

    $flatbed = new Flatbed;

    $flatbed->api('config', new Config, true);
    $flatbed->api('request', $request = new Request, true);
    
    $flatbed->api('events', new Events, true);

    $flatbed->api('extensions', new Extensions, true);
    $flatbed->api('fields', new Fields, true);

    $flatbed->api('pages', $pages = new Pages, true);
    $flatbed->api('users', new Users, true);
    $flatbed->api('roles', new Roles, true);

    $flatbed->api('templates', new Templates, true);
    $flatbed->api('views', new Views, true);

    $flatbed->api('session', new Session, true);
    // $flatbed->api('logger', new Logger, true);
    
    // run the app
    if ($page = $pages->get($request->path)) {
        echo $page->render();
    }
    else {
        // TODO :  I'd like to see if I can do this without the need for a page and template
        echo $pages->get('404')->render();
    }
    

    // end performance tracking
    $end = microtime(true);
    $creationtime = round(($end - $start), 2);
    echo "<!-- Page created in $creationtime seconds. (" . getMemoryUse() .") -->";

} catch(FlatbedException $exception) {
    echo $exception->render($flatbed("config"));
}
