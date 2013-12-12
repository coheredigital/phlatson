<?php

abstract class FieldtypeObject extends Fieldtype{

	protected $objectType = null;

	protected function outputFormat($value){
		$object = new $this->objectType("$value");
		return $object;
	}
	protected function editFormat($value){
		return (string) $value;
	}


}