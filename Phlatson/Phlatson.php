<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Gives access to the internal Api
 *      - Allow for extending of & addition to class methods
 */

class Phlatson
{

    use ApiAccess;

    private string $name;
    private string $path;
    private string $siteFolder;

    private Config $config;


    // private $dataFolders = [
    //     "Page"
    // ]

    public function __construct(string $path)
    {
        // setup default config and import site config
        $this->name = basename($path);
        $this->path = \rtrim($path, "/");
        $this->config = new Config(ROOT_PATH . "Phlatson/data/config/data.json");
        $siteConfig = new Config("$path/config/data.json");
        $this->config->merge($siteConfig);

        // create finder (I know, yuck)
        $finder = new Finder(ROOT_PATH);
        $this->api('finder', $finder);

        // add system path mappings
        foreach ($this->config->get('storage') as $className => $folder) {
            // TODO: create a better method of ensuring folder names are good
            // possible add an array that define which class names are default and their locations
            $name = strtolower($className);
            $finder->addPathMapping($className, ROOT_PATH . "Phlatson/data/{$name}s/"); // system folder
        }

        // add path mappings from config
        foreach ($this->config->get('storage') as $className => $folder) {
            $finder->addPathMapping($className, "{$this->path}{$folder}");
        }
    }

}
