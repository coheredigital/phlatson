<?php 

class AdminFieldEdit extends AdminPageEdit {

	public function setup(){
		parent::setup();

		$this->page = api("fields")->get(api("input")->get->name);

		$this->title = $this->page->label;


	}


	protected function addSettingsFields(){
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



	public function render(){


		$this->addDefaultFields();

		$submitButtons =  api("extensions")->get("FieldtypeFormActions");
		$submitButtons->dataObject = $this->page;
		$submitButtonsGroup = api("extensions")->get("MarkupFieldgroup");
		$submitButtonsGroup->add($submitButtons);


		$this->addSettingsFields();

		// $output = $this->tabs->render();
		$this->form->add($submitButtonsGroup);
		return $this->form->render();

	}

}
