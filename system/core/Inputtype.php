<?php

abstract class Inputtype extends Extension{

	protected $attributes = array();
	protected $field;

	final public function __construct(Field $field = null){
		parent::__construct();

		$this->field = $field;
		$this->attribute('class', 'field-input '.$this->className);
		$this->setup();
		$this->addStyles();
		$this->addScripts();
	}


	protected function setup(){}
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

	
	public function renderInput(){
		$attributes = $this->getAttributes();
		$output = "<input {$attributes} type='text' name='{$this->name}' id='Input_{$this->name}' value='{$this->value}'>";
		return $output;
	}

	protected function getAttributes(){
		$string = "";

		foreach ($this->attributes as $key => $value) {
			$string .= "{$key}='$value' ";
		}
		return trim($string);

	}




	public function attribute($key, $value = false){
		if (!$value && isset($this->attributes["$key"])) return $this->attributes["$key"];
		else $this->attributes["{$key}"] = (string) $value; // only string values accepted for attributes

	}


}