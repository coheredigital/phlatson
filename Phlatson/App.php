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

    public string $name;
    public array $domains = [];

    protected string $path;
    protected string $siteFolder;

    // core api classes
    protected Config $config;
    protected Finder $finder;
    protected Request $request;
    protected Page $page;
    protected User $user;

    public function __construct(string $path)
    {

        $this->api('app', $this);
        $this->request = new Request();

        // setup default config and import site config
        $this->name = basename($path);
        $this->path = \rtrim($path, "/");
        $this->config = new Config(ROOT_PATH . 'Phlatson/data/config/data.json');
        $siteConfig = new Config($path . '/config/data.json');
        $this->config->merge($siteConfig);

        // create finder (I know, yuck)
        $this->finder = new Finder();
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
            $this->finder->addPathMapping($className, $this->path . $folder);
        }

        // add domains
        foreach ($this->config->get('domains') as $domain) {
            $this->addDomain($domain);
        }

    }

    public function addDomain(string $domain)
    {
        if (in_array($domain, $this->domains)) {
            return;
        }
        $this->domains[] = $domain;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function execute(Request $request)
    {

        // determine the requested page
        $url = $request->url;
        $page = $this->finder->get("Page", $url);
        if (!$page) {
            // TODO: improved 404 handle (throw exception)
            $page = $this->finder->get("Page", "/404");
        }

        // get and render the view with API variables as default
        $view = $page->template()->view([
            "app" => $this,
            "finder" => $this->finder,
            "page" => $page,
            "request" => $request,
        ]);

        if ($view instanceof View) {
            echo $view->render();
        }

    }

}
