<?php

/*

Core class to connect most other system classes, stores system wide api variables

 */

abstract class Core{

	private static $apis = array();

	/*Init function sets up default variables and other tasks*/
	public static function init(Config $config){
		self::api('config', $config);
		self::api('fields', new Fields());
		self::api('templates', new Templates());
		self::api('pages', new Pages());
		self::api('input', new Input());
		self::api('session', new Session());
		self::api('files', new Files());
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

	/*
	return $apis array;
	*/
	public static function apiList(){
		return new ArrayObject(self::$apis);
	}

	public static function setApi($name, $value){
		if (!isset(self::$apis[$name])) self::$apis[$name] = $value;
	}

}