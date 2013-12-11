<?php

/*

Core class to connect most other system classes, stores system wide api variables

 */

abstract class Core{

	// private static $apis = array();
	protected static $registry = null;
	private $className = null;

	/*Init function sets up default variables and other tasks*/
	public static function init(Config $config){
		self::api('config', $config);
		self::api('fields', new Fields());
		self::api('templates', new Templates());
		self::api('pages', new Pages());
		self::api('input', new Input());
		self::api('users', new Users());
		self::api('session', new Session());
		self::api('files', new Files());
	}

	// method to get reference to chache api class
	// if $value provide, use as a "setter"
	public static function api($name = null, $object = null){
		if (!isset(self::$registry)) self::$registry = new Registry();
		if (is_null($name)) return self::$registry;
		if (is_null($object)) {
			return self::$registry->get($name);
		}
        else if (!is_null($object)){
			self::setApi($name, $object);	
		}
	}

	public static function setApi($name, $value){
		if (!isset(self::$registry)) self::$registry = new Registry();
		self::$registry->set($name, $value);
	}


	public function className(){

		if (!isset($this->className)) $this->className = get_class($this);
		return $this->className;	

	} 

	public function __toString(){
		return $this->className();
	}

}