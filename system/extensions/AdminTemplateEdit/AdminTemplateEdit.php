<?php

class AdminTemplateEdit extends AdminObjectEdit
{

    protected function setupObject(){

        if(api("input")->get->new){

            $this->object = new Template();

            $templateName = api("input")->get->template;
            $this->object->template = $templateName;

            $this->title = "New Template";

        }
        else{
            $name = api("input")->get->name;
            $this->object = api("templates")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;

        }

    }

//    private function getSettings()
//    {
//        $value = $this->object->icon;
//        $input = api("extensions")->get("FieldtypeText");
//        $input->label = "Icon";
//        $input->columns = 12;
//        $input->value = $value;
//        $input->attribute("name", "icon");
//
//        $fieldset = api("extensions")->get("MarkupFormtab");
//        $fieldset->label = "Settings";
//        $fieldset->add($input);
//        $this->form->add($fieldset);
//
//    }

    private function addContentFieldset()
    {
        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = "Content";
        $object = $this->object;
        $template = $object->get("template");
        $fields = $template->get("fields");
        foreach ($fields as $field) {
            $fieldtype = $field->type;
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
