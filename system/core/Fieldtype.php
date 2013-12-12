<?php

abstract class Fieldtype extends Plugin{
	protected $attributes = array();
	protected $field;
	// contains defaults settings and there defaults values
	// can be extended by other Fieldtypes
	protected $settings = array();

	public function __construct(Field $field = null){
		$this->field = $field;
		$this->data = new stdClass(); // create a stdClass to hold get and set request made by DataObject

		$this->set('label', '');
		$this->set('columns', 12);

		$this->attribute('class', 'field-input '.$this->className);


		$this->setup();
		$this->addStyles();
		$this->addScripts();
	}

	/**
	 * alias for the three available formatting methods, 
	 * allows passing of type, can auto determing required method
	 * @param  mixed $value raw value from SimpleXML object
	 * @param  string $type  
	 * @return mixed        determined by fieldtype object
	 */
	final public function format($value, $type){
		switch ($type) {
			case 'output':
				return $this->outputFormat($value);
				break;
			case 'raw':
			case 'edit':
				return $this->editFormat($value);
				break;
			case 'save':
				return $this->saveFormat($value);
				break;
			default:
				return null;
				break;
		}

	}

	protected function editFormat($value){
		return (string) $value;
	}
	protected function outputFormat($value){
		return (string) $value;
	}


	/**
	 * saveFormat should return type DomElement
	 */

	public function saveFormat( $name, $value ){

		$dom = new DomDocument;
        $node = $dom->createElement("$name", "$value");
        $dom->appendChild($node);

		return $dom->documentElement;

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
	protected function renderInput(){
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