<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Gives access to the internal Api
 *      - Allow for extending of & addition to class methods.
 */
class App
{
    public string $name;
    public array $domains = [];

    protected string $path;
    // protected string $siteFolder;

    // core api classes
    protected Config $config;
    protected Finder $finder;
    protected Request $request;
    protected Page $page;
    protected User $user;

    public function __construct(
        string $path,
        Request $request,
        Config $config
    ) {
        // setup default config and import site config
        $this->name = basename($path);
        $this->path = $this->sanitizePath($path);
        $this->request = $request;
        $this->finder = new Finder($this, $this->path);
        $this->config = $config;

        // add default system path mappings
        foreach ($config->get('storage') as $className => $folder) {
            $folderName = strtolower($className);
            $path = $this->sanitizePath(__DIR__ . '/data/');
            $folder = $this->sanitizePath($path . "{$folderName}s/");
            $dataFolder = new DataFolder($path, "{$folderName}s", $this);
            $this->finder->map($className, $folder);
            $this->finder->addDataFolder($className, $dataFolder);
        }

        // add path mappings from config
        foreach ($config->get('storage') as $className => $folderName) {
            $folder = $this->sanitizePath($this->path . $folderName);
            $dataFolder = new DataFolder($this->path, $folderName, $this);
            $this->finder->map($className, $folder);
            $this->finder->addDataFolder($className, $dataFolder);
        }

        // add domains
        foreach ($config->get('domains') as $domain) {
            $this->addDomain($domain);
        }
    }

    // GETTERS
    public function name(): string
    {
        return $this->name;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function domains(): array
    {
        return $this->domains;
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function addDomain(string $domain): void
    {
        if (in_array($domain, $this->domains)) {
            return;
        }
        $this->domains[] = $domain;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function sanitizePath(string $path): string
    {
        $path = \realpath($path);
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path . '/');
        $path = rtrim($path, '/') . '/';

        return $path;
    }

    public function getPage(string $uri): Page
    {
        return $this->finder->get('Page', $uri);
    }

    public function getTemplate(string $uri): Template
    {
        return $this->finder->get('Template', $uri);
    }

    public function getField(string $uri): Field
    {
        return $this->finder->get('Field', $uri);
    }

    public function getFieldtype(string $uri): ?Fieldtype
    {
        return $this->finder->get('Fieldtype', $uri);
    }

    public function getView(string $uri): View
    {
        return $this->finder->getView($uri);
    }

    public function execute()
    {
        // determine the requested page
        $page = $this->getPage($this->request->url);
        if (!$page) {
            // TODO: improved 404 handle (throw exception)
            $page = $this->finder->get('Page', '/404');
        }
        $this->page = $page;

        // get and render the view with API variables as default
        $view = $page->template()->view([
            'app' => $this,
            'finder' => $this->finder,
            'page' => $page,
            'request' => $this->request,
        ]);

        if ($view instanceof View) {
            echo $view->render();
        }
    }
}
