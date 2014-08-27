<?php

class AdminEditPage extends AdminEdit
{

    public function setup()
    {
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


        $save = new Route();
        $save
            ->path("/pages/edit/:all")
            ->method("POST")
            ->parent($adminRoute)
            ->callback(
                function ($url) {
                    $page = api("pages")->get($url);
                    $this->object = $page;
                    $this->processSave();
                }
            );

        $upload = new Route();
        $upload
            ->path("/pages/upload:all")
            ->method("POST")
            ->parent($adminRoute)
            ->callback(
                function ($url) {
                    $page = api("pages")->get($url);
                    $this->processFiles($page);
                }
            );

        api('router')->add($edit);
        api('router')->add($new);
        api('router')->add($save);
        api('router')->add($upload);

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

        $input = api("extensions")->get("FieldtypePageFiles");
        $input->label = "Files";
        $input->attribute("name", "parent");
        $input->files = $this->object->files;

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

    protected function addFields()
    {
        $this->addDefaultFields();
        $this->addFilesFields();

    }



}