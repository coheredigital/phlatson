<?php

final class Config{

	public $data = array();


	static $instance = null;
	public static function Instance(){ 
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }


	public function get($name){
		$value = $this->data["$name"];
		return $value;
	}

	public function __get($name){
		return $this->get($name);
	}


	public function set($name, $value){
		$this->data["$name"] = $value;
		return $value;
	}

	public function __set($name, $value){
		return $this->set($name, $value);
	}



}