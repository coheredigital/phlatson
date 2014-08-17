<?php

class AdminPageEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        $config = api("config");

        $pageEdit = new Route;
        $pageEdit->path("/pages/edit:all");
        $pageEdit->parent(api('admin')->route);
        $pageEdit->callback(
            function ($url) {
                $this->object = api("pages")->get($url);
                $this->template = $this->object->template;
                $this->title = $this->object->title;
                $this->render();
            }
        );

        $pageNew = new Route;
        $pageNew->path("/pages/new/:any/:all");
        $pageNew->parent(api('admin')->route);
        $pageNew->callback(
            function ($template, $parent) {
                $this->object = new Page();
                $this->object->template = $template;
                $this->object->parent = $parent;
                $this->title = "New Page";
                $this->render();
            }
        );


        $saveRoute = new Route();
        $saveRoute->path("/pages/edit/:all");
        $saveRoute->parent(api('admin')->route);
        $saveRoute->callback(
            function ($url) {
                $page = api("pages")->get($url);
                $this->object = $page;
                $this->processSave();
            }
        );
        $saveRoute->method("POST");

        api('router')->add($pageEdit);
        api('router')->add($pageNew);
        api('router')->add($saveRoute);


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


    public function processFiles()
    {
        if (!empty($_FILES)) {
            $uploader = new Upload($this->object);
            $uploader->send($_FILES);
        }
    }

    public function processSave()
    {

        $this->object->save();
        api("session")->redirect(
            api("input")->query
        );

    }


    public function render()
    {

        $this->form = api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;

        $this->addDefaultFields();
        $this->addFilesFields();

        $admin = api("admin");
        $admin->title = "Edit Page";
        $admin->output = $this->form->render();
        $admin->render();

    }


}