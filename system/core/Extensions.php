<?php


class Extensions extends ObjectArray{

	protected $root = "extensions/";
	protected $singularName = "extension";


	public function get($name) {

		if(is_object($name)) $name = (string) $name; // stringify $object

		if(!isset($this->data[$name]) && !$this->allowRootRequest) return false;
		$object = new $name();
		return $object;


	}

}