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

	public function registerApp(string $name):void
	{
		// create the config
		$config = new Config(__DIR__ . '/data/config.json');
		$config->merge(new Config($this->rootPath . '/' . $name . '/config.json'));

		$app = new App(
			$this->rootPath . '/' . $name,
			$this->request,
			$config
		);

		foreach ($app->domains as $domain) {
			$this->apps[$domain] = $app;
		}
	}

	public function app(): ?App
	{
		if (!isset($this->apps[$this->request->domain])) {
			return null;
		}

		return $this->apps[$this->request->domain];
	}
}