<?php

class AdminFieldEdit extends Extension
{

    public static function getInfo() {
        return array(
            'title' => 'ColorPicker',
            'version' => 104,
            'summary' => 'Choose your colors the easy way.',
            'href' => 'http://processwire.com/talk/topic/865-module-colorpicker/page__gopid__7340#entry7340',
            'requires' => array("FieldtypeColorPicker")
        );
    }


    public function setup()
    {

        $config = api("config");

        Router::get( "/{$config->adminUrl}/fields/edit/(:any)" , function( $name ){
            $this->object = api("fields")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;

            $this->render();

        });

        Router::get( "/{$config->adminUrl}/fields/new/" , function(){
            $this->object = new Field();
            $this->object->template = "field";
            $this->object->parent = $parent;
            $this->title = "New Page";

            $this->render();
        });


        Router::post( "/{$config->adminUrl}/fields/edit/(:any)" , function( $name ){

            $page = api("fields")->get($name);
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
        $admin->title = "Edit Field";
        $admin->output = $this->form->render();
        $admin->render();

    }

}
