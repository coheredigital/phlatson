<?php

class AdminPageEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        $this->form = api("extensions")->get("MarkupEditForm");

        if(!api("input")->get->new){
            $this->object = api("pages")->get(api("input")->get->name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;
        }
        else{
            $this->object = new Page;

            // set parent from get parameter
            $parentUrl = api("input")->get->parent;
            $this->object->parent = api("pages")->get($parentUrl); // TODO reevaluate, I shouldn't need to actually retrieve this object. maybe just verify its valid, not sure
            $this->title = "New Page";
        }

        // detect files submissions
        if (!empty($_FILES)) {
            $this->processFiles();
        }

        // process save
        if (count(api("input")->post)) {
            $this->object->save(api("input")->post);
            api("session")->redirect(api("input")->query);
        }

    }


    protected function addSettingsFields()
    {

        $settings = api("extensions")->get("MarkupFieldset");
        $settings->label = "Settings";
        $settings->add($this->getFieldTemplate());
        $settings->add($this->getFieldParentSelect());

        $this->form->add($settings);
    }

    protected function addFilesFields()
    {

        $settings = api("extensions")->get("MarkupFieldset");
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
        $input = api("extensions")->get("FieldtypeFiles");
        $input->label = "Files";
        $input->columns = 12;
        $input->value = $value;
        $input->attribute("name", "parent");

        return $input;
    }

    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFieldset");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->get("fields");
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->label = $field->label;
            // $fieldtype->columns = $field->attributes('col') ? (int)$field->attributes('col') : 12;

            if (!is_null($this->object)) {
                $name = $field->get("name");
                $fieldtype->value = $this->object->getUnformatted($name);
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
        $submitButtons->dataObject = $this->object;
        $submitButtonsGroup = api("extensions")->get("MarkupFieldset");
        $submitButtonsGroup->add($submitButtons);


        $this->addFilesFields();
        $this->addSettingsFields();

        // $output = $this->tabs->render();
        $this->form->add($submitButtonsGroup);
        return $this->form->render();

    }

    public function processFiles(){

        $uploader = new Upload($this->object);
        $uploader->send($_FILES);

    }

}