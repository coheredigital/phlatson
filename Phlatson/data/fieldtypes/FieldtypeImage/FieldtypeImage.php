<?php

namespace Phlatson;

class FieldtypeImage
{

	protected $value = null;

	public function set($value)
	{
		$this->value = $value;
	}

	public function get()
	{
		return new Image($this->value);
	}

}
