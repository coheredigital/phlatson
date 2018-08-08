<?php

namespace Phlatson;

class Validator
{

	private function __construct()
	{
		// prevents instantiation
	}

	public static function url(string $url) : string
	{
		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
		$url = trim($url, '/');
		return "/{$url}/";
	}

	public static function path(string $path) : bool
	{
		return file_exists($path);
	}

}
