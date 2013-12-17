<?php 

class AdminPageEdit extends Extension {
	protected $title;
	protected $form;
	protected $page;

	public function setup(){


		$this->form = api("extensions")->get("MarkupEditForm");
		$this->tabs = api("extensions")->get("MarkupTabs");
		$this->page = api("pages")->get(api("input")->get->name);

		$this->title = $this->page->title;

		// process save
		if (count(api("input")->post)) {
			$this->page->save(api("input")->post);
			api("session")->redirect(api("input")->query);
		}

	}

	protected function addSettingsFields(){

		$settings = api("extensions")->get("MarkupFieldgroup");
		$settings->label = "Settings";
		$settings->add($this->getFieldTemplate());

		$this->form->add($settings);
	}

	protected function getFieldTemplate(){

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

		$input =  api("extensions")->get("FieldtypeSelect");
		$input->label = "Template";
		$input->columns = 12;
		$input->setOptions($selectOptions);
		$input->value = $value;
		$input->attribute("name", "template");
		return $input;
	}

	protected function addDefaultFields(){

		$fieldgroup = api("extensions")->get("MarkupFieldgroup");
		$fieldgroup->label = "{$this->title}";
		$fields = $this->page->template->fields;
		foreach ($fields as $field) {
			$input = $field->type;
			$input->label = $field->label;
			$input->columns = $field->attributes('col') ? (int) $field->attributes('col') : 12;
			$input->value = $this->page->getUnformatted($field->name);
			$input->attribute("name",$field->name);
			$fieldgroup->add($input);
		}

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
