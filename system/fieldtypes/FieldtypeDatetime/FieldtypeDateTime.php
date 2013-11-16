<?php

class FieldtypeDateTime extends Fieldtype{

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/themes/default.css");
		api('config')->styles->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/themes/default.date.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.date.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/$this->className.js");
	}

	public function format($value, $format){
		$value = date((string) $format, (int) $value);
		return $value;
	}

	public function render(){

		$attributes = $this->getAttributes();

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