<?php namespace markup;

class EditForm {
	// array of field markup to be rendered
	public $page;
	public $template;

	public $formID;

	private $colCount = 0;

	public function __construct($dataObject){
		$this->page = $dataObject;
	}

	public function render(){
		$formFields = "";
		$row = "";

		foreach ($this->page->template->fields() as $field) {
			
			if ($field instanceof \Field ) {

				$ft = (string) $field->fieldtype;

				if ($ft) {


					$this->colCount += $field->attributes('col');
					$fieldType = new $ft();

					

					$fieldType->set('label', $field->label);
					$fieldType->name = $field->name;
					$fieldType->set('value',$this->page->getEditable("$field"));
					$fieldType->set('columns',$field->attributes('col'));
					$row .= $fieldType->render();


				}
			}


			if ($this->colCount === 12) {
				$formFields .= "<div class='row'>{$row}</div>";
				$row = "";
				$this->colCount = 0;
			}

		}

		



		$submit = "<button form='pageEdit' type='submit' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
		$output = "<form id='pageEdit' action='' method='POST' role='form'>".$formFields.$submit."</form>";
		
		return $output;


	}


}