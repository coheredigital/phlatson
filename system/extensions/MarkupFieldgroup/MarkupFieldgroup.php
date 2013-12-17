<?php 
class MarkupFieldgroup extends Extension{
	// array of field markup to be rendered

	public $fields = array();
	public $label;
	private $tabs;

	public function add(\Fieldtype $field){
		$this->fields[] = $field;
	}


	public function render(){
		$colCount = 0;
		$fields = "";

		foreach ($this->fields as $field) {
			
			if ($colCount === 0)
				$fields .= "<div class='row'>"; // open new row div
			
			if (is_object($field)) {

				$colCount += $field->columns;
				$fields .= $field->render();

				if ($colCount === 12) {
					$fields .= "</div>"; // close row div
					$colCount = 0; // reset colCount
				}
			}
		}

		if ($this->label) {
			$label = "<legend class='row'>{$this->label}</legend>";
		}
		
		$output = "<feildset>{$label}{$fields}{$submit}</feildset>";
		return $output;


	}


}