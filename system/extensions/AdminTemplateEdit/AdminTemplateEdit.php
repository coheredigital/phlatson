<?php

class AdminTemplateEdit extends Extension
{

    private $tabs;
    private $form;
    private $page;

    public function setup()
    {
        $this->form = api("extensions")->get("MarkupEditForm");
        $this->tabs = api("extensions")->get("MarkupTabs");

        if( $name = api("input")->get->name){
            $this->page = api("templates")->get($name);
            $this->template = api("templates")->get("template");
        }



        // process save
        if (count(api("input")->post)) {
            $this->page->save(api("input")->post);
            api("session")->redirect(api("input")->query);
        }


    }

    private function getSettings()
    {
        $value = $this->page->icon;
        $input = api("extensions")->get("FieldtypeText");
        $input->label = "Icon";
        $input->columns = 12;
        $input->value = $value;
        $input->attribute("name", "icon");

        $fieldset = api("extensions")->get("MarkupFieldset");
        $fieldset->label = "Settings";
        $fieldset->add($input);
        $this->form->add($fieldset);

    }

    private function addContentFieldset()
    {
        $fieldset = api("extensions")->get("MarkupFieldset");
        $fieldset->label = "Content";
        $page = $this->page;
        $template = $page->get("template");
        $fields = $template->get("fields");
        foreach ($fields as $field) {
            $input = $field->type;
            $input->label = $field->label;
            $input->columns = $field->attributes('col') ? (int)$field->attributes('col') : 12;
            $input->value = $this->page->{$field->name};
//            $input->attribute("name", $field->name);
            $fieldset->add($input);
        }
        $this->form->add($fieldset);
    }


    public function render()
    {


        $this->addContentFieldset();


        $submitButtons = api("extensions")->get("FieldtypeFormActions");
        $submitButtons->dataObject = $this->page;
        $submitButtonsGroup = api("extensions")->get("MarkupFieldset");
        $submitButtonsGroup->add($submitButtons);


        // $output = $this->tabs->render();
        $this->form->add($submitButtonsGroup);
        return $this->form->render();

    }

}
