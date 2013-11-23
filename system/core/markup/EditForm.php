<?php namespace markup;

class EditForm {
	// array of field markup to be rendered
	public $page;
	public $template;
	public $fields = array();
	public $formID;

	private $colCount = 0;

	public function __construct($dataObject){
		$this->page = $dataObject;
		/*
		
		FIX THIS HARD CODING OF FIELD, NEEDS TO BE DYNAMIC

		 */
		$this->template = new \Template('field', api('config')->paths->systemTemplates.'field');
		$this->fields = $this->template->fields();
	}

	public function render(){
		$fieldsOutput = "";
		$rowFields = "";

		foreach ($this->fields as $field) {
			// var_dump($field);
			if ($field instanceof \Field ) {

				$ft = (string) $field->fieldtype;

				if ($ft) {


					$this->colCount += $field->attributes('col');
					$fieldType = new $ft();
					$fieldType->set('label', $field->label);
					$fieldType->set('name', $field->name);
					$fieldType->set('value',$this->page->getEditable("$field"));
					$fieldType->set('columns',$field->attributes('col'));
					$rowFields .= $fieldType->render();
				}
			}
		}

		if ($this->colCount === 12) {
			$fieldsOutput .= "<div class='row'>{$rowFields}</div>";
			$rowFields = "";
			$colCount = 0;
		}

		$submit = "<button form='pageEdit' type='button' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
		$output = "<form id='pageEdit' action='' method='POST' role='form'>{$fieldsOutput}{$submit}</form>";

		return $output;
	}


}