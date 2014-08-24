<?php

class AdminTemplateEdit extends Extension
{


    public function setup()
    {

        $templateEdit = new Route;
        $templateEdit->path("/templates/edit/:any");
        $templateEdit->parent(api("admin")->route);
        $templateEdit->callback(
            function ($name) {
                $this->object = api("templates")->get($name);
                $this->template = $this->object->template;
                $this->title = $this->object->title;
                $this->render();
            }
        );

        $templateNew = new Route;
        $templateNew->path("/templates/new/");
        $templateNew->parent(api("admin")->route);
        $templateNew->callback(
            function () {
                $this->object = new Template();
                $this->object->template = "template";
                $this->object->parent = $parent;
                $this->title = "New Page";
                $this->render();
            }
        );


        $templateSave = new Route;
        $templateSave->path("/templates/edit/:any");
        $templateSave->method("POST");
        $templateSave->parent(api("admin")->route);
        $templateSave->callback(
            function ($name) {
                $page = api("templates")->get($name);
                $this->object = $page;
                $this->processSave();
            }
        );


        api('router')->add($templateEdit);
        api('router')->add($templateNew);
        api('router')->add($templateSave);


    }


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = "Main";

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
