<?php

namespace Phlatson;

class Filemanager
{

	protected $root;

	public function __construct(string $root)
	{
		if (!file_exists()) {
			throw new Exceptions\PhlatsonException("Root path must be valid folder, '$root' not found.");		
		}

		$this->root = $root;
	}

	// TODO: Flesh out this concept
	public static function saveData($data, string $path, $name = "data") : bool
	{
		
		$filepath = ROOT_PATH . $path;
		if (!file_exists($filepath)) {
			throw new Exceptions\PhlatsonException("Path ($filepath) does not exist!");
		}
		
		$json = json_encode($data);
		
		$file = "{$filepath}{$name}.json";
		return file_put_contents($file, $json) ? true : false;

	}

	// TODO: Flesh out this concept
	public static function getData(string $path, $name = "data") : ?array
	{
		
		$filepath = ROOT_PATH . trim($path, "/") . "/";
		$file = "{$filepath}{$name}.json";
		if (!file_exists($file)) {
			return null;
		}
		$data = file_get_contents($file);
		$json = json_decode($data, true);
		
		return $json;

	}

}
