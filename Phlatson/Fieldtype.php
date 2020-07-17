<?php

class Fieldtype
{

	protected $value = null;

	public function __construct($value)
	{
		$this->value = $value;
		$this->init();
	}

	public function init(): void
	{
	}
}
