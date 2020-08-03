<?php

namespace Phlatson;

/**
 * This Class marks a file system folder as a storage location for DataFiles (JSON)
 * could define folder permission, app ownership, readonly status, etc
 *
 */
class DataFolder
{
	protected $paths = [];
	protected $cache = [];

	public function addPath(string $path)
	{
		$path = $this->sanitizePath($path);

		if (!file_exists($path)) {
			throw new \Exception('Invalid file');
		}

		$this->paths[$path] = null;
	}

	protected function sanitizePath(string $path): string
	{
		$path = \realpath($path);
		$path = str_replace(DIRECTORY_SEPARATOR, '/', $path . '/');
		$path = rtrim($path, '/') . '/';
		return $path;
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
		foreach ($this->paths() as $path => $value) {
			$file = $path . $uri . '/data.json';

			if (!\file_exists($file)) {
				$this->cache[$uri] = null;
				continue;
			}

			// create the dataFile and cache
			$dataFile = new DataFile($file, $this);
			$this->cache[$uri] = $dataFile;

			return $dataFile;
		}
	}

    public function paths(): array
    {
        return array_reverse($this->paths);
    }

}
