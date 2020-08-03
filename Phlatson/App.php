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
        Config $config,
        Finder $finder
    ) {

        // setup default config and import site config
        $this->name = basename($path);
        $this->path = $this->sanitizePath($path);
        $this->request = $request;
        $this->finder = $finder;
        $this->config = $config;

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

    public function sanitizePath(string $path): string
	{
		$path = \realpath($path);
		$path = str_replace(DIRECTORY_SEPARATOR, '/', $path . '/');
		$path = rtrim($path, '/') . '/';
		return $path;
	}

    public function execute()
    {
        // determine the requested page
        $url = $this->request->url;
        $page = $this->finder->get('Page', $url);
        if (!$page) {
            // TODO: improved 404 handle (throw exception)
            $page = $this->finder->get('Page', '/404');
        }

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
