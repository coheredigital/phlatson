<?php

class AdminPageEdit extends Extension
{
    protected $title;
    protected $form;
    protected $page;
    protected $template;

    protected $new = false;

    public function setup()
    {

        $this->form = api("extensions")->get("MarkupEditForm");


        $this->page = api("pages")->get(api("input")->get->name);
        $this->template = $this->page->template;
        $this->title = $this->page->title;


        // process save
        if (count(api("input")->post)) {
            $this->page->save(api("input")->post);
            api("session")->redirect(api("input")->query);
        }

    }

    public function setNew()
    {
        $this->new = true;
    }

    protected function addSettingsFields()
    {

        $settings = api("extensions")->get("MarkupFieldset");
        $settings->label = "Settings";
        $settings->add($this->getFieldTemplate());
        $settings->add($this->getFieldParentSelect());

        $this->form->add($settings);
    }

    protected function getFieldTemplate()
    {

        $template = $this->template;
        $value = $this->template->name;
        $selectOptions = array();
        $templates = api("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }


        $input = api("extensions")->get("FieldtypeSelect");
        $input->label = "Template";
        $input->columns = 6;
        $input->setOptions($selectOptions);
        $input->value = $value;
        $input->attribute("name", "template");
        return $input;
    }

    protected function getFieldParentSelect()
    {

        $value = $this->page->parent;

        $selectOptions = array();
        $templates = api("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }
        $input = api("extensions")->get("FieldtypeSelectPage");
        $input->label = "Parent";
        $input->columns = 6;
        $input->value = $value;
        $input->attribute("name", "parent");

        return $input;
    }

    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFieldset");
        $fieldset->label = "{$this->title}";
        $defaultFields = $this->page->defaultFields;
        $fields = $this->page->template->fields();
        foreach ($fields as $field) {
            $input = $field->type;
            $input->label = $field->label;
            $input->columns = $field->attributes('col') ? (int)$field->attributes('col') : 12;

            if (!is_null($this->page)) {
                $input->value = $this->page->getUnformatted($field->name);
            }
            $input->attribute("name", $field->name);

            $fieldset->add($input);
        }

        $this->form->add($fieldset);

    }


    public function render()
    {


        $this->addDefaultFields();

        $submitButtons = api("extensions")->get("FieldtypeFormActions");
        $submitButtons->dataObject = $this->page;
        $submitButtonsGroup = api("extensions")->get("MarkupFieldset");
        $submitButtonsGroup->add($submitButtons);


        $this->addSettingsFields();

        // $output = $this->tabs->render();
        $this->form->add($submitButtonsGroup);
        return $this->form->render();

    }

}