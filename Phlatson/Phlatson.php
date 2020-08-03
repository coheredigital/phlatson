<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Allow for extending of & addition to class methods.
 */
class Phlatson
{

    protected string $rootPath;
    protected Request $request;
    protected array $apps = [];

    public function __construct(string $path, Request $request)
    {
        // setup default config and import site config
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
        $this->rootPath = \rtrim($path, '/');
        $this->request = $request;
    }

    public function registerApp(string $name)
    {
        // create the config
        $config = new Config(__DIR__ . '/data/config.json');
        $config->merge(new Config($this->rootPath . "/" . $name . '/config.json'));

        $finder = new Finder($this->rootPath);

        // add default system path mappings
        foreach ($config->get('storage') as $className => $folder) {
            $folder = strtolower($className);
            $finder->addPathMapping($className, $this->rootPath . "/Phlatson/data/{$folder}s/");
        }

        // add path mappings from config
        foreach ($config->get('storage') as $className => $folder) {
            $finder->addPathMapping($className, $this->rootPath . "/" . $name . "/" . $folder);
        }

        $app = new App(
            $this->rootPath . '/' . $name,
            $this->request,
            $config,
            $finder
        );

        foreach ($app->domains as $domain) {
            $this->apps[$domain] = $app;
        }
    }


    public function app($domain): ?App
    {
        if (!isset($this->apps[$domain])) {
            return null;
        }

        return $this->apps[$domain];
    }

    public function execute()
    {
        if ($app = $this->app($this->request->domain)) {
            $app->execute($this->request);
        }
    }
}
