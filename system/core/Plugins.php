<?php


class Plugins extends ObjectArray{

	protected $root = "plugins/";
	protected $singularName = "plugin";


	public function get($name) {

		if(is_object($name)) $name = (string) $name; // stringify $object

		if(!isset($this->data[$name]) && !$this->allowRootRequest) return false;
		$object = new $name();
		return $object;
		
		return false;
	}

}