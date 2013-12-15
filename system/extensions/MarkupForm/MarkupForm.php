<?php 
class MarkupForm {
	// array of field markup to be rendered
	public $dataObject;
	public $formID;
	public $fields = array();


	public function setup($dataObject){
		$this->dataObject = $dataObject;
		$this->fields = $dataObject->template->fields;
		$this->dataObject->setFormat("edit");
	}

	public function addFieldgroup(\Fieldgroup $fieldgroup){
		$this->fields[] = $fieldgroup;
	}

	public function addField(\Field $field){
		$this->fields[] = $field;
	}

	public function render(){
		$colCount = 0;
		$formFields = "";

		foreach ($this->fields as $field) {
			
			if ($colCount === 0)
				$formFields .= "<div class='row'>"; // open new row div
			

			if ($field instanceof \Field ) {

				$fieldColumns = $field->attributes('col');
				$colCount += $fieldColumns;

				$fieldtype = $field->type;

				$fieldtype->set('label', $field->label);
				$fieldtype->name = $field->name;


				// uses set value, otherwise retrieve value from object being edited by name
				$value = $this->value ? $this->value : $this->dataObject->get("$field->name");
				$fieldtype->value = $value;

				$fieldtype->set('columns',$fieldColumns);
				$formFields .= $fieldtype->render();

				if ($colCount === 12) {
					$formFields .= "</div>"; // close row div
					$colCount = 0; // reset colCount
				}

			}
			elseif($field instanceof \Fieldgroup){

				$group = $field;
				$groupFields = $group->fields();

			}
		}

		



		$submit = "<button form='pageEdit' type='submit' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
		$output = "<form id='pageEdit' class='edit-form' method='POST' role='form'>".$formFields.$submit."</form>";
		
		return $output;


	}


}