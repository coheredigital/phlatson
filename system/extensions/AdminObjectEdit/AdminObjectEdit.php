<?php

class AdminObjectEdit extends Extension // TODO :  make abstact (won't work for now because of how list of extensions is retrieved)
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        if( !api("input")->get->name ) return false;

        $this->setupObject();
        $this->setupForm();
        $this->processSave();
    }


    protected function setupObject(){}

    protected function setupForm(){
        $this->form = api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;
    }


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->setObject($this->object);
            $fieldtype->label = $field->label;

            if (!is_null($this->object)) {
                $name = $field->get("name");
                $fieldtype->attribute("name", $name);
                $fieldset->add($fieldtype);
            }

        }

        $this->form->add($fieldset);

    }



    public function processFiles(){
        if (!empty($_FILES)) {
            $uploader = new Upload($this->object);
            $uploader->send($_FILES);
        }
    }

    public function processSave(){
        if (count(api("input")->post)) {
            $this->object->save(api("input")->post);
            api("session")->redirect(api("input")->query);
        }
    }


}