<?php

class Fieldtype{

	// protected $className;

	// public function __constuct(){
	// 	$this->className = get_class($this);
	// }

	public function format($value, $format){
		return $value;
	}

	public function render($name, $value){
		return "<input class='field-input ".get_class($this)."' type='text' name='$name' id='' value='$value'>";
	}

}