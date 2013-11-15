<?php


abstract class Core{
	private static $apis = array();

	/*Init function sets up default variables and other tasks*/
	public static function init(Config $config){
		self::api('config', $config);
		self::api('fieldtypes', new Fieldtypes());
	}

	// method to get reference to chache api class
	// if $value provide, use as a "setter"
	public static function api($name, $object = null){

		if (isset(self::$apis[(string) $name])) return self::$apis[(string) $name];
		// if an object is passed, try to set it unless it already exists
		if (!isset(self::$apis[$name]) && !is_null($object)){
			self::setApi($name, $object);	
		}
	}

	public static function setApi($name, $value){
		if (!isset(self::$apis[$name])) self::$apis[$name] = $value;
	}

}