<?php

class Config{
	

	public static function get($name){

		$value = $this->_data->$name;

		return $value;
	}

	public function __get($name){
		return $this->get($name);
	}


}