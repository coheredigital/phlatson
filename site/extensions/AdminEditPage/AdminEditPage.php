<?php

class AdminEditPage extends AdminEdit
{

    public function setup()
    {
        $adminRoute = app('admin')->route;

        $edit = new Route;
        $edit->path("/pages/edit:all")
            ->parent($adminRoute)
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

        $newSave = new Route;
        $newSave->path("/pages/new/:any/:all")
            ->parent($adminRoute)
            ->method("POST")
            ->callback(
                function ($template, $parent) {
//                    $page = api("pages")->get($url);
//                    $this->object = $page;
                    $this->processSave();
                }
            );

        // temp solution to the above issue
        $newRootChild = new Route;
        $newRootChild->path("/pages/new/:any/")
            ->parent($adminRoute)
            ->callback(
                function ($template) {

                    $this->object = new Page();
                    $this->object->template = $template;
                    $this->object->parent = "/";

                    $this->title = "New Page";
                    $this->render();
                }
            );

//
//        $saveNew = new Route();
//        $saveNew
//            ->path("/pages/new/:any/")
//            ->method("POST")
//            ->parent($adminRoute)
//            ->callback(
//                function ($url) {
//                    $page = api("pages")->get($url);
//                    $this->object = $page;
//                    $this->processSave();
//                }
//            );

        $save = new Route();
        $save
            ->path("/pages/edit/:all")
            ->method("POST")
            ->parent($adminRoute)
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
            ->parent($adminRoute)
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

    public function processSave()
    {

        $this->object->save();
        app("session")->redirect(
            app("request")->url
        );

    }

    protected function addFields()
    {
        $this->addDefaultFields();
        $this->addFilesFields();

    }



}