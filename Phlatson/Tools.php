<?php

namespace Phlatson;

class Tools
{

	public static function sanitizeUrl(string $url)
	{
		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
		$url = trim($url, '/');
		return "/{$url}/";
	}


}
