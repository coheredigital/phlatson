<?php

namespace Phlatson;

class Fieldtype extends DataObject
{

	protected $value = null;

	public function __construct()
	{
		// $this->value = $value;
		$this->init();
	}

	public function init(): void
	{
	}
	
}
