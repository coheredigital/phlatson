<?php

class FieldtypeSelect extends Fieldtype{

	protected $selectOptions = array();

	public function render(){


		$attributes = $this->getAttributes();
		$options = $this->getOptions();


		$output  = "<div class='col col-{$this->columns}'>";
			$output  .= "<div class='field-item'>";
				$output .= "<div class='field-heading'>";
					$output .= "<label for=''>";
					$output .= "{$this->label}";
					$output .= "</label>";
				$output .= "</div>";
				$output .= "<div class='field-content'>";		
					$output .= "<select {$attributes} name='{$name}' id='Input_{$name}'>{$options}</select>";	
				$output .= "</div>";		
			$output .= "</div>";
		$output .= "</div>";
		return $output;

	}

	protected function getOptions(){
		$output = "";
		foreach ($this->selectOptions as $name => $value) {
			$selected = $this->value == $value ? "selected='selected'" : null;
			$output .= "<option {$selected} value='{$value}'>{$name}</option>";
		}
		return $output;
	}


}