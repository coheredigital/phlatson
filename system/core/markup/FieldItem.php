<?php namespace markup;

class FieldItem{

	public $name;
	public $value;
	public $columns;
	public $label;
	public $attributes = array();

	public function __construct($field = false){
		if ($field !== false || !$field instanceof Field) return false;
	}

	public function render(){

		$output  = "<div class='col col-{$this->columns}'>";
			$output  .= "<div class='field-item'>";
				$output .= "<div class='field-heading'>";
					$output .= "<label for=''>";
					$output .= "{$this->label}";
					$output .= "</label>";
				$output .= "</div>";
				$output .= "<div class='field-content'>";		
					$output .= "<input {$attributes} type='text' name='{$this->name}' id='Input_{$this->label}' value='{$this->value}'>";	
				$output .= "</div>";		
			$output .= "</div>";
		$output .= "</div>";
		return $output;
	}


}