
<?php

class FieldtypeMarkdown extends Fieldtype{


	protected function setup(){
		require_once "Parsedown.php";
	}

	protected function addStyles(){
		// api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/epiceditor/themes/base/epiceditor.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/epiceditor/js/epiceditor.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.js");
	}


	public function outputFormat($value, $format = false){
		$value = Parsedown::instance()->parse($value);
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