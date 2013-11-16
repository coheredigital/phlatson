<?php

abstract class Fieldtype extends DataObject{
	protected $attributes = array();


	public function __construct(){

		$this->className = $this->className();

		$this->set('label', '');
		$this->set('columns', 12);


		$this->attribute('name', '');
		$this->attribute('class', 'field-input '.$this->className);
		$this->attribute('id', '');

		$this->addStyles();
		$this->addScripts();

	}



	public function format($value, $format){
		return $value;
	}

	public function setupField(Field $field){
		
	}

	public function setupBasic($name, $value, $label = ""){
		$this->name = $name;
		$this->label = $label ? $label : $name;
		$this->value = $value;
	}

	protected function addStyles(){}
	protected function addScripts(){}
	public function render(){}


	protected function getAttributes(){
		$string = "";

		foreach ($this->attributes as $key => $value) {
			// if ($key == "class") 
			// 	$string .= $this->getInputClass();
			$string .= "{$key}='$value' ";
		}
		return trim($string);

	}

	// protected function getInputClass(){	
	// 	$defaultClass = $this->attributes['class'];
	// 	$columns = "col-{$this->columns}";
	// 	$class = "class='{$defaultClass} {$columns}'";
	// 	return $class;
	// }

	public function get($name){
		return $this->data["{$name}"];
	}

	public function set($key, $value){
		$this->data["{$key}"] = $value;
	}

	public function attribute($key, $value = false){
		if (!$value) return $this->attributes["{$key}"];
		else $this->attributes["{$key}"] = (string) $value; // only string values accepted for attributes

	}





}