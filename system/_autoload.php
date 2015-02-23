<?php
spl_autoload_register('classLoader');


function classLoader($className)
{

    $systemPath = ROOT_PATH . "system" . DIRECTORY_SEPARATOR;
    $className = normalizeDirectorySeperators($className);

    $file = $systemPath . $className . ".php";

    if (!is_file($file)) {
        $extensionsSitePath = ROOT_PATH . "site" . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR;
        $extensionsSystemPath = ROOT_PATH . "system" . DIRECTORY_SEPARATOR . "extensions" . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR;

        $extensionSite = $extensionsSitePath . $className . ".php";
        $extensionSystem = $extensionsSystemPath . $className . ".php";

        if (!is_file($extensionSite) && !is_file($extensionSystem)) {
            throw new FlatbedException("Extension {$className} does not exist / cannot be found!");
        }

        if (is_file($extensionSystem)) $file = $extensionSystem;
        // a site extension can replace a System core extension
        if (is_file($extensionSite)) $file = $extensionSite;


    }

    require_once $file;

}

function normalizeDirectorySeperators($path){
    return str_replace("\\", DIRECTORY_SEPARATOR, $path);
}