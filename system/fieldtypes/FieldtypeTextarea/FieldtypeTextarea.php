
<?php

class FieldtypeTextarea extends Fieldtype{



	

	public function format($value, $format = false){
		$value = htmlspecialchars_decode($value);
		return $value;
	}

	public function saveFormat($value){
		return $value;
	}


	public function render(){

		$attributes = $this->getAttributes();

		$output  = "<div class='col col-{$this->columns}'>";
			$output  = "<div class='field-item'>";
				$output .= "<div class='field-heading'>";
					$output .= "<label for=''>";
					$output .= "{$this->label}";
					$output .= "</label>";
				$output .= "</div>";
				$output .= "<div class='field-content'>";
					$output .= "<textarea {$attributes} name='{$this->name}' id='Input_{$this->name}' cols='30' rows='10'>{$this->value}</textarea>";
				$output .= "</div>";
			$output .= "</div>";
		$output .= "</div>";
		return $output;
	}

}