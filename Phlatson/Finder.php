<?php 

namespace Phlatson;

class Finder
{

	protected $root;

	public function __construct(string $rootPath) {
		$this->root = $rootPath;
	}

}
