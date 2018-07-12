<?php

namespace Phlatson;

class Sanitizer
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

	public static function path(string $path) : string
	{
		$path = realpath($path) . DIRECTORY_SEPARATOR;
		$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
		$path = rtrim($path, "/") . "/"; // prevent extra trailing slashes
		return $path;
	}

}
