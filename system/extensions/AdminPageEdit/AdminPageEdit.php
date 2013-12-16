<?php 

class AdminPageEdit extends Extension {

	private $page;
	public function setPage(\DataObject $page){
		$this->page = $page;
	}


	private function getTemplateField(){
		$value =  $this->page->template->name;
		$selectOptions = array();
		$templates = api("templates")->all();
		foreach ($templates as $t) {
			$selectOptions["$t->label"] = "$t->name";
		}
		$input =  api("extensions")->get("FieldtypeSelect");
		$input->label = "Template";
		$input->columns = 12;
		$input->setOptions($selectOptions);
		$input->value = $value;
		$input->attribute("name", "template");

		return $input;
	}


	public function render(){

		$form = api("extensions")->get("MarkupEditForm");
		$submitButtons =  api("extensions")->get("FieldtypeFormActions");
		$submitButtons->dataObject = $this->page;
		$form->add($submitButtons);
		$fields = $this->page->template->fields;

		foreach ($fields as $field) {
			$input = $field->type;
			$input->label = $field->label;
			$input->columns = $field->attributes('col') ? (int) $field->attributes('col') : 12;
			$input->value = $this->page->getUnformatted($field->name);
			$input->attribute("name",$field->name);
			$form->add($input);
		}



		$form->add($this->getTemplateField());


		$form->add($submitButtons);
		return $form->render();

	}

}
