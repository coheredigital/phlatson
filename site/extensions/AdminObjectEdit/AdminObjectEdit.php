<?php

class AdminObjectEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

//        if( !api::get("input")->get->name && !api::get("input")->get->new ) return false;

        $this->setupObject();
        $this->setupForm();
        $this->processSave();
    }


    protected function setupObject(){
        return false;
    }

    protected function setupForm(){
//        $this->form->object = $this->object;
    }


    protected function addDefaultFields()
    {

        $fieldset = api::get("extensions")->get("MarkupFormtab");
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




    protected function addSettingsFields()
    {

        $tab = api::get("extensions")->get("MarkupFormtab");
        $tab->label = "Settings";

        $tab->add( $this->getModifiedField() );

        $this->form->add($tab);
    }


    protected function getModifiedField()
    {

        $fieldtype = api::get("extensions")->get("FieldtypeText");

        $fieldtype->label = "Last Modified";
        $fieldtype->value = date("Y/m/d - H:m",$this->object->modified);
        $fieldtype->columns = 12;
        $fieldtype->attribute("name", "parent");

        return $fieldtype;
    }





}