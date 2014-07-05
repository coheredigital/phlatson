<?php

class AdminTemplateEdit extends Extension
{

    private $tabs;
    private $form;
    private $object;

    public function setup()
    {
        $this->form = api("extensions")->get("MarkupEditForm");
        $this->tabs = api("extensions")->get("MarkupTabs");

        if( $name = api("input")->get->name){
            $this->object = api("templates")->get($name);
            $this->template = api("templates")->get("template");
        }

        // process save
        if (count(api("input")->post)) {
            $this->object->save(api("input")->post);
            api("session")->redirect(api("input")->query);
        }


    }

    private function getSettings()
    {
        $value = $this->object->icon;
        $input = api("extensions")->get("FieldtypeText");
        $input->label = "Icon";
        $input->columns = 12;
        $input->value = $value;
        $input->attribute("name", "icon");

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = "Settings";
        $fieldset->add($input);
        $this->form->add($fieldset);

    }

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
