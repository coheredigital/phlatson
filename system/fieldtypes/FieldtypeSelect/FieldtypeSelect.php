<?php

class FieldtypeSelect extends Fieldtype{


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
					$output .= "<select {$attributes} name='{$name}' id='Input_{$name}'><option value='CAN'>Canada</option><option value='USA'>United States</option></select>";	
				$output .= "</div>";		
			$output .= "</div>";
		$output .= "</div>";
		return $output;

	}

}