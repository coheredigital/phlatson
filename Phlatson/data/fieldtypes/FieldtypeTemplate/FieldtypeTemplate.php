<?php

namespace Phlatson;

class FieldtypeTemplate
{

	protected $value = null;


	public function set($value)
	{
		$this->value = $value;
	}

	public function get()
	{
		return new Template($this->value);
	}


}
