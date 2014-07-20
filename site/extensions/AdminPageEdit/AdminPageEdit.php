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


    protected function addFilesFields()
    {

        $tab = api("extensions")->get("MarkupFormtab");
        $tab->label = "Files";
        $tab->add($this->getFieldFiles());

        $this->form->add($tab);
    }


    protected function getFieldFiles()
    {

        $fieldtype = api("extensions")->get("FieldtypePageFiles");
        $fieldtype->setObject($this->object);
        $fieldtype->label = "Files";
        $fieldtype->columns = 12;
        $fieldtype->attribute("name", "parent");

        return $fieldtype;
    }




    public function render()
    {

        $this->addDefaultFields();
        $this->addFilesFields();

        return $this->form->render();

    }


}