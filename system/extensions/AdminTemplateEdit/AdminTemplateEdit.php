<?php 

class AdminTemplateEdit extends Extension {

	private $tabs;
	private $form;
	private $page;

	public function setup(){
		$this->form = api("extensions")->get("MarkupEditForm");
		$this->tabs = api("extensions")->get("MarkupTabs");
		$this->page = api("templates")->get(api("input")->get->name);
	}

	private function getSettings(){
		$value =  $this->page->icon;
		$input =  api("extensions")->get("FieldtypeText");
		$input->label = "Icon";
		$input->columns = 12;
		$input->value = $value;
		$input->attribute("name", "icon");

		$fieldgroup = api("extensions")->get("MarkupFieldgroup");
		$fieldgroup->label = "Settings";
		$fieldgroup->add($input);
		$this->form->add($fieldgroup);

	}

	private function addContentFieldgroup(){

		$fieldgroup = api("extensions")->get("MarkupFieldgroup");
		$fieldgroup->label = "Content";
		$fields = $this->page->template->fields;
		foreach ($fields as $field) {
			$input = $field->type;
			$input->label = $field->label;
			$input->columns = $field->attributes('col') ? (int) $field->attributes('col') : 12;
			$input->value = $this->page->{$field->name};
			$input->attribute("name",$field->name);
			$fieldgroup->add($input);
		}

		$this->form->add($fieldgroup);

	}


	public function render(){


		$this->addContentFieldgroup();



		$submitButtons =  api("extensions")->get("FieldtypeFormActions");
		$submitButtons->dataObject = $this->page;
		$submitButtonsGroup = api("extensions")->get("MarkupFieldgroup");
		$submitButtonsGroup->add($submitButtons);


		// $output = $this->tabs->render();
		$this->form->add($submitButtonsGroup);
		return $this->form->render();

	}

}
