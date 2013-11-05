<?php


class Fields{


	public function get($name){
		$field = new $name();
		return $field;
	}

}