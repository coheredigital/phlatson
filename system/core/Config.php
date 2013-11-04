<?php

class Config{


	public function set($name, $value){
		if (!$this->{$name}) $this->{$name} = $value;
	}


	public function __set($name, $value){
		return $this->set($name, $value);
	}

}