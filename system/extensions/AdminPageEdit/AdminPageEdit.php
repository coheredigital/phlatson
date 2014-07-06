<?php

class AdminPageEdit extends AdminObjectEdit
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        $this->setupObject();
        $this->setupForm();

        $this->processFiles();
        $this->processSave();

    }

    protected function setupObject(){

        if(api("input")->get->new){

            $this->object = new Page();

            // set the template first so following value can be set properly
            $templateName = api("input")->get->template;
            $this->object->template = $templateName;

            // set parent from get parameter
            $parentUrl = api("input")->get->parent;
            $this->object->parent = $parentUrl;

            $this->title = "New Page";

        }
        else{
            $name = api("input")->get->name;
            $this->object = api("pages")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;

        }

    }



    protected function addSettingsFields()
    {

        $settings = api("extensions")->get("MarkupFormtab");
        $settings->label = "Settings";
        $settings->add($this->getFieldTemplate());
        $settings->add($this->getFieldParentSelect());

        $this->form->add($settings);
    }

    protected function addFilesFields()
    {

        $settings = api("extensions")->get("MarkupFormtab");
        $settings->label = "Files";
        $settings->add($this->getFieldFiles());

        $this->form->add($settings);
    }

    protected function getFieldTemplate()
    {

        $field = api("fields")->get("template");

        $fieldtype = $field->type;
        $fieldtype->setPage($this->object);
        return $fieldtype;
    }

    protected function getFieldParentSelect()
    {

        $field = api("fields")->get("parent");

        $value = $this->object->parent->directory;

        $fieldtype = $field->type;
        $fieldtype->label = "Parent";
        $fieldtype->columns = 6;
        $fieldtype->value = $value;
        $fieldtype->attribute("name", "parent");

        return $fieldtype;
    }

    protected function getFieldFiles()
    {

        $value = $this->object->files();

        $selectOptions = array();
        $templates = api("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }
        $input = api("extensions")->get("FieldtypePageFiles");
        $input->label = "Files";
        $input->columns = 12;
        $input->value = $value;
        $input->attribute("name", "parent");

        return $input;
    }




    public function render()
    {

        $this->addDefaultFields();
        $this->addFilesFields();
        $this->addSettingsFields();

        return $this->form->render();

    }


}