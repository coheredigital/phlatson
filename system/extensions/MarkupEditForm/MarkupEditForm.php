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

			if (is_object($element)) {

				$colCount += $element->columns;
				$formFields .= $element->render();

			}


		}

		$output = "<form id='pageEdit' class='edit-form' method='POST' role='form'>".$formFields.$submit."</form>";

		return $output;


	}


}