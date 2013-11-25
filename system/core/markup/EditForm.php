<?php namespace markup;

class EditForm {
	// array of field markup to be rendered
	public $page;
	public $template;

	public $formID;

	public function __construct($dataObject){
		$this->page = $dataObject;
	}

	public function render(){
		$colCount = 0;
		$formFields = "";

		$fields = $this->page->template->fields;

		foreach ($fields as $field) {
			
			if ($colCount === 0)
				$formFields .= "<div class='row'>"; // open new row div
			

			if ($field instanceof \Field ) {

				$fieldColumns = $field->attributes('col');
				$colCount += $fieldColumns;

				$fieldtype = $field->type();
				$fieldtype->set('label', $field->label);
				$fieldtype->name = $field->name;
				$fieldtype->set('value',$this->page->getEditable("$field"));
				$fieldtype->set('columns',$fieldColumns);
				$formFields .= $fieldtype->render();

				if ($colCount === 12) {
					$formFields .= "</div>"; // close row div
					$colCount = 0; // reset colCount
				}

			}



		}

		



		$submit = "<button form='pageEdit' type='submit' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
		$output = "<form id='pageEdit' class='edit-form' method='POST' role='form'>".$formFields.$submit."</form>";
		
		return $output;


	}


}