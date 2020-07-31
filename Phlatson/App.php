<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Gives access to the internal Api
 *      - Allow for extending of & addition to class methods
 */

class App
{

    use ApiAccess;

    protected string $name;
    protected string $path;
	protected string $siteFolder;
	protected array $domains = [];

    protected Config $config;
    protected Finder $finder;


    public function __construct(string $path)
    {
        // setup default config and import site config
        $this->name = basename($path);
        $this->path = \rtrim($path, "/");
        $this->config = new Config(ROOT_PATH . "Phlatson/data/config/data.json");
        $siteConfig = new Config("$path/config/data.json");
        $this->config->merge($siteConfig);

        // create finder (I know, yuck)
        $this->finder = new Finder(ROOT_PATH);
        $this->api('finder', $this->finder);

		// PATH MAPPINGS ========================================
        // add system path mappings
        foreach ($this->config->get('storage') as $className => $folder) {
            // TODO: create a better method of ensuring folder names are good
            // possible add an array that define which class names are default and their locations
            $name = strtolower($className);
            $this->finder->addPathMapping($className, ROOT_PATH . "Phlatson/data/{$name}s/"); // system folder
        }

        // add path mappings from config
        foreach ($this->config->get('storage') as $className => $folder) {
            $this->finder->addPathMapping($className, "{$this->path}{$folder}");
        }
    }



}
