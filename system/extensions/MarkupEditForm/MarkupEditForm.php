<?php 
class MarkupEditForm extends Extension{
	// array of field markup to be rendered
	public $dataObject;
	public $formID;
	public $fields = array();

	public function add(\Fieldtype $input){
		$this->fields[] = $input;
	}

	public function render(){
		$colCount = 0;
		$formFields = "";

		foreach ($this->fields as $field) {
			
			if ($colCount === 0)
				$formFields .= "<div class='row'>"; // open new row div
			
			if (is_object($field)) {

				$colCount += $field->columns;
				$formFields .= $field->render();

				if ($colCount === 12) {
					$formFields .= "</div>"; // close row div
					$colCount = 0; // reset colCount
				}

			}


		}

		$output = "<form id='pageEdit' class='edit-form' method='POST' role='form'>".$formFields.$submit."</form>";

		return $output;


	}


}