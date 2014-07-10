<?php

class AdminTemplateEdit extends AdminObjectEdit
{

    protected function setupObject(){

        if(api::get("input")->get->new){

            $this->object = new Template();

            $templateName = api::get("input")->get->template;
            $this->object->template = $templateName;

            $this->title = "New Template";

        }
        else{
            $name = api::get("input")->get->name;
            $this->object = api::get("templates")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;

        }

    }


    private function addContentFieldset()
    {
        $fieldset = api::get("extensions")->get("MarkupFormtab");
        $fieldset->label = "Content";
        $fields = $this->object->template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->setObject($this->object);
            $fieldtype->label = $field->label;
            $fieldtype->value = $this->object->{$field->name};
            $fieldset->add($fieldtype);
        }
        $this->form->add($fieldset);
    }


    public function render()
    {
        $this->addContentFieldset();
        return $this->form->render();

    }

}
