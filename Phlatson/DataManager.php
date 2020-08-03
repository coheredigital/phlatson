<?php

use Phlatson\DataFile;

class DataManager
{
	/**
	 * Manages finding, updating, moving, deleting of DataFile(s)
	 */
	protected string $rootPath;
	protected $cache = [];

	public function __construct(string $path)
	{
		// setup default config and import site config
		$this->rootPath = \rtrim($path, '/');
	}

	public function get(string $path): ?DataFile
	{

		$path = trim($path, "/");

		if (isset($this->cache[$path])) {
			return $this->cache[$path];
		}

		$file = $this->rootPath . '/' . $path;

		if (!file_exists($file)) {
			return null;
		}

		$dataFile = new DataFile($file);
	}
}
