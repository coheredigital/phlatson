<?php

class DataManager
{
	/**
	 * Exclusively manager finder and returning DataFile(s)
	 */
	protected string $rootPath;

	public function __construct(string $path)
	{
		// setup default config and import site config
		$this->rootPath = \rtrim($path, '/');
	}
}
