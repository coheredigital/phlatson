<?php

class FieldtypeTemplate extends Fieldtype{


	protected function outputFormat($value){
		$object = $this->api("templates")->get("$value");
		return $object;
	}
	protected function editFormat($value){
		return (string) $value;
	}


}