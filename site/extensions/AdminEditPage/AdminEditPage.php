<?php

class AdminEditPage extends AdminEdit
{

    public function setup()
    {

        $edit = new Route;
        $edit->path("/pages/edit:all")
            ->parent("admin")
            ->callback(
                function ($url) {
                    $this->object = app("pages")->get($url);
                    $this->template = $this->object->template;
                    $this->title = $this->object->title;
                    $this->render();
                }
            );

        // TODO: this method still doesn't properly support adding new pages under the root page
        $new = new Route;
        $new->path("/pages/new/:any/:all")
            ->parent("admin")
            ->callback(
                function ($template, $parent) {
                    $this->object = new Page();
                    $this->object->template = $template;
                    $this->object->parent = $parent;
                    $this->title = "New Page";
                    $this->render();
                }
            );

        $newSave = new Route;
        $newSave->path("/pages/new/:any/:all")
            ->parent("admin")
            ->method("POST")
            ->callback(
                function ($template, $parent) {
                    // TODO: the duplication of code here doesn't seem natural, reevaluate

                    $this->object = new Page();
                    $this->object->template = $template;
                    $this->object->parent = $parent;
                    $this->processSave();
                }
            );

        // temp solution to the above issue
        $newRootChild = new Route;
        $newRootChild->path("/pages/new/:any/")
            ->parent("admin")
            ->callback(
                function ($template) {

                    $this->object = new Page();
                    $this->object->template = $template;
                    $this->object->parent = "/";

                    $this->title = "New Page";
                    $this->render();
                }
            );


        $save = new Route();
        $save
            ->path("/pages/edit/:all")
            ->method("POST")
            ->parent("admin")
            ->callback(
                function ($url) {
                    $page = app("pages")->get($url);
                    $this->object = $page;
                    $this->processSave();
                }
            );

        $upload = new Route();
        $upload
            ->path("/pages/upload:all")
            ->method("POST")
            ->parent("admin")
            ->callback(
                function ($url) {
                    $page = app("pages")->get($url);
                    $this->processFiles($page);
                }
            );

        app("router")->add($edit);
        app("router")->add($new);
        app("router")->add($save);
        app("router")->add($newSave);
        app("router")->add($upload);

    }




    protected function addFilesFields()
    {

        $tab = app("extensions")->get("MarkupFormtab");
        $tab->label = "Files";
        $tab->add($this->getFieldFiles());

        $this->form->add($tab);
    }


    protected function getFieldFiles()
    {

        $input = app("extensions")->get("FieldtypePageFiles");
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

    protected function addFields()
    {
        $this->addDefaultFields();
        $this->addFilesFields();

    }



}