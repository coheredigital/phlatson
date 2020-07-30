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
    private Config $config;
    private Finder $finder;

    // private $dataFolders = [
    //     "Page"
    // ]

    public function __construct(string $path)
    {
        // setup default config and import site config
        $this->name = basename($path);
        $this->path = $path;
        $this->config = new Config(__DIR__ . "/data/config/data.json");
        $siteConfig = new Config("$path/config/data.json");
        $this->config->merge($siteConfig);

        // create finder (I know, yuck)
        $this->finder = new Finder($this->path);

        // add path mappings
        foreach ($this->config->get('storage') as $className => $folder) {
            // TODO: create a better method of ensuring folder names are good
            // possible add an array that define which class names are default and their locations
            $name = strtolower($className);
            // $this->finder->addPathMapping($className, "/data/{$name}s/"); // system folder
            $this->finder->addPathMapping($className, $folder);
        }
    }
}
