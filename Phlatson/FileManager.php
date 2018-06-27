<?php 

namespace Phlatson;

class FileManager
{

	protected $root;

	public function __construct()
	{
		$this->root = ROOT_PATH;
	}

	/**
	 * Return a full validated path from a root relative URL
	 *
	 * @param string $url
	 * @return string
	 */
	function get(string $url) : string
	{
		$path = $this->root . $url;
		if (file_exists($path)) {
			return $path;
		}
		return "";
	}


	public function exists(string $url)
	{
		$path = $this->root . $url;
		return file_exists($path);
	}
}
