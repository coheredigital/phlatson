<?php

namespace Phlatson;

abstract class Factory
{

	protected string $rootPath;

	public function __construct(string $rootPath)
	{
		$this->rootPath = $rootPath;
	}

	abstract public function make(string $uri): Object

}