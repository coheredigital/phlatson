<?php

class AdminTemplateEdit extends Extension
{


    public function setup()
    {

        $config = api("config");

        Router::get( "/{$config->adminUrl}/templates/edit/:any" , function( $name ){
            $this->object = api("templates")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;

            $this->render();

        });

        Router::get( "/{$config->adminUrl}/templates/new/" , function(){
            $this->object = new Template();
            $this->object->template = "template";
            $this->object->parent = $parent;
            $this->title = "New Page";

            $this->render();
        });


        Router::post( "/{$config->adminUrl}/templates/edit/:any" , function( $name ){

            $page = api("templates")->get($name);
            $this->object = $page;

            $this->processSave();

        });


    }


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->get("template");
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->label = $field->label;

            if (!is_null($this->object)) {
                $name = $field->name;
                $fieldtype->setObject($this->object);
                $fieldtype->attribute("name", $name);
                $fieldset->add($fieldtype);
            }

        }

        $this->form->add($fieldset);

    }


    public function render()
    {

        $this->form = api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;

        $this->addDefaultFields();

        $admin = api("admin");
        $admin->title = "Edit Template";
        $admin->output = $this->form->render();
        $admin->render();

    }

}
