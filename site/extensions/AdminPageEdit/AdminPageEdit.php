<?php

class AdminPageEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        $api = api();

        $adminRoute = api('admin')->route;

        $edit = new Route;
        $edit->path("/pages/edit:all")
            ->parent($adminRoute)
            ->callback(
                function ($url) {
                    $this->object = api("pages")->get($url);
                    $this->template = $this->object->template;
                    $this->title = $this->object->title;
                    $this->render();
                }
            );

        $new = new Route;
        $new->path("/pages/new/:any/:all")
            ->parent($adminRoute)
            ->callback(
                function ($template, $parent) {
                    $this->object = new Page();
                    $this->object->template = $template;
                    $this->object->parent = $parent;
                    $this->title = "New Page";
                    $this->render();
                }
            );


        $actions = new Route();
        $actions
            ->path("/pages/:any:all")
            ->method("POST")
            ->parent($adminRoute)
            ->callback(
                function ($action, $url) {
                    switch ($action) {
                        case "save":
                            $page = api("pages")->get($url);
                            $this->object = $page;
                            $this->processSave();
                            break;
                        case "upload":
                            $page = api("pages")->get($url);
                            $this->processFiles($page);
                            break;
                    }

                }
            );

        api('router')->add($edit);
        api('router')->add($new);
        api('router')->add($actions);

    }


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;
        foreach ($fields as $field) {

            $input = $field->input ? new $field->input : new InputText;
            if($field->type) $input->fieldtype($field->type);
            $input->value = $this->object->get($field->name);

            $fieldset->add($input);

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

        $input = api("extensions")->get("InputPageFiles");
        $input->setObject($this->object);
        $input->label = "Files";
        $input->attribute("name", "parent");

        return $input;
    }


    public function processFiles($object)
    {
        if (!empty($_FILES)) {
            $uploader = new Upload($object);
            $uploader->send($_FILES);
        }
    }

    public function processSave()
    {

        $this->object->save();
        api("session")->redirect(
            api("request")->url
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