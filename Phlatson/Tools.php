<?php

namespace Phlatson;

class Tools
{
	
	public static function classnameFromPath(string $path) : string
	{
		$path_parts = explode('/', trim($path, "/"));

		$classname = array_shift($path_parts);
		$classname = ucfirst($classname);
		$classname = substr($classname, 0, -1);
		$classname = "\Phlatson\\$classname";

		return $classname;

	}

}
