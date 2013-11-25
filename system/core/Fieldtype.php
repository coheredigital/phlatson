<?php

abstract class Fieldtype extends DataObject{
	protected $attributes = array();

// contains defaults settings and there defaults values
// can be extended by other Fieldtypes
protected $settings = array();

	public function __construct(){

		$this->set('label', '');
		$this->set('columns', 12);


		// $this->attribute('name', '');
		$this->attribute('class', 'field-input '.$this->className);
		// $this->attribute('id', '');

		$this->setup();
		$this->addStyles();
		$this->addScripts();
	}

	// alias for the three available formatting methods, allows passing of type, can auto determing required method
	public function format($value, $type = "output"){

		switch ($type) {
			case 'output':
				return $this->outputFormat($value);
				break;
			case 'edit':
				return $this->editFormat($value);
				break;
			case 'save':
				return $this->saveFormat($value);
				break;
			default:
				return false;
				break;
		}

	}

	public function editFormat($value){
		return $value;
	}

	public function outputFormat($value, $format = false){
		return $value;
	}

	public function saveFormat($value){
		return $value;
	}




	protected function setup(){}

	public function setupBasic($name, $value, $label = ""){
		$this->name = $name;
		$this->label = $label ? $label : $name;
		$this->value = $value;
	}

	protected function addStyles(){}
	protected function addScripts(){}

	// we will default to rendering a basic text field since it will be the most common inout type for most field types
	public function render(){

		
		$input = $this->renderInput();

		$output  = "<div class='col col-{$this->columns}'>";
			$output  .= "<div class='field-item'>";
				if ($this->label) {
					$output .= "<div class='field-heading'>";
						$output .= "<label for=''>";
						$output .= "{$this->label}";
						$output .= "</label>";
					$output .= "</div>";
				}
				$output .= "<div class='field-content'>";		
					$output .= $input;	
				$output .= "</div>";		
			$output .= "</div>";
		$output .= "</div>";
		return $output;
	}
	protected function renderInput(){
		$attributes = $this->getAttributes();
		$output = "<input {$attributes} type='text' name='{$this->name}' id='Input_{$this->name}' value='{$this->value}'>";
		return $output;
	}

	protected function getAttributes(){
		$string = "";

		foreach ($this->attributes as $key => $value) {
			// if ($key == "class") 
			// 	$string .= $this->getInputClass();
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
		if (!$value && isset($this->attributes["$key"])) return $this->attributes["$key"];
		else $this->attributes["{$key}"] = (string) $value; // only string values accepted for attributes

	}





}