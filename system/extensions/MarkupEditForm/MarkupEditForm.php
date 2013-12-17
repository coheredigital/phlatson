<?php 
class MarkupEditForm extends Extension{
	// array of field markup to be rendered
	public $dataObject;
	public $formID;
	public $formElements = array();

	public function add(MarkupFieldgroup $element){
		$this->formElements[] = $element;
	}


	public function render(){
		$colCount = 0;
		$formFields = "";


		foreach ($this->formElements as $element) {
			if ($colCount === 0)
				$formFields .= "<div class='row'>"; // open new row div
			
			if (is_object($element)) {

				$colCount += $element->columns;
				$formFields .= $element->render();

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