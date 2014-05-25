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

        if(!api("input")->get->new){
            $this->page = api("pages")->get(api("input")->get->name);
            $this->template = $this->page->template;
            $this->title = $this->page->title;
        }
        else{
            $this->page = new Page;
            $this->page->template = api("templates")->get(api("input")->get->template);

            // set parent from get parameter
            $parentUrl = api("input")->get->parent;
            $this->page->parent = api("pages")->get($parentUrl); // TODO reevaluate, I shouldn't need to actually retrieve this object. maybe just verify its valid, not sure

            $this->template = $this->page->template;
            $this->title = "New Page";
        }


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

        $template = $this->get("template");
        $value = $template->get("name");
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

    protected function getFieldFiles()
    {

        $value = $this->page->files();

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
        $fieldset->label = $this->get("title");

        $template = $this->template;
        $fields = $template->get("fields");
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->label = $field->label;
            $fieldtype->columns = $field->attributes('col') ? (int)$field->attributes('col') : 12;

            if (!is_null($this->page)) {
                $name = $field->get("name");
                $fieldtype->value = $this->page->getUnformatted($name);
                $fieldtype->attribute("name", $name);
                $fieldset->add($fieldtype);
            }

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