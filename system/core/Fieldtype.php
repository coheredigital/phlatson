<?php

abstract class Fieldtype extends DataObject{
	private $className;
	protected $attributes = array();


	public function __construct(){

		$this->className = get_class($this);

		$this->set('label', '');
		$this->set('label', '');


		$this->attribute('name', '');
		$this->attribute('class', 'field-input '.$this->className);
		$this->attribute('id', '');

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



	public function render(){

		$attributes = $this->getAttributes();

		$output  = "<div class='field-item'>";
		$output .= "<div class='field-heading'>";
		$output .= "<label for=''>";
		$output .= "{$this->label}";
		$output .= "</label>";
		$output .= "</div>";
		$output .= "<div class='field-content'>
				<input {$attributes} type='text' name='{$this->name}' id='Input_{$this->label}' value='{$this->value}'>
			</div>
		</div>";
		return $output;
	}


	public function getAttributes(){
		$string = "";

		foreach ($this->attributes as $key => $value) {
			$string .= "{$key}='$value' ";
		}
		return trim($string);

	}


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