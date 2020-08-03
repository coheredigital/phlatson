<?php

namespace Phlatson;

/**
 * This Class marks a file system folder as a storage location for DataFiles (JSON)
 * could define folder permission, app ownership, readonly status, etc
 *
 */
class DataFolder
{
	protected App $app;
	protected string $path;
	protected array $cache = [];

	public function __construct(string $uri, App $app)
	{

		$this->app = $app;
		$this->path = $app->path() . trim($uri,"/") . "/";

		if (!file_exists($this->path)) {
			throw new \Exception('Invalid file');
		}

	}



	public function get(string $uri): ?DataFile
	{
		// sanitizer the URI
		$uri = \strtolower($uri);
		$uri = \trim($uri, '/');

		// check already loaded
		if (isset($this->cache[$uri])) {
			return $this->cache[$uri];
		}

		// find in filesystem

		$file = $this->path . $uri . '/data.json';
		if (!\file_exists($file)) {
			$this->cache[$uri] = null;
			return null;
		}

		// create the dataFile and cache
		$dataFile = new DataFile($file, $this);
		$this->cache[$uri] = $dataFile;

		return $dataFile;

	}


}
