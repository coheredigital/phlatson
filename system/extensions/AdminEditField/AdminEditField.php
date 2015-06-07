<?php

class AdminEditField extends AdminEdit
{


    public function setup()
    {

        $fieldRoute = new Route;
        $fieldRoute
            ->path("fields/edit/:any")
            ->parent("admin")
            ->callback(
            function ($name) {
                $this->object = app("fields")->get($name);
                $this->title = "Edit Field";
                $this->render();
            }
        );
        app("router")->add($fieldRoute);


        $newFieldRoute = new Route;
        $newFieldRoute
            ->path("fields/new/")
            ->parent("admin")
            ->callback(
            function () {
                $this->object = new Field();
                $this->object->template = "field";
                $this->object->parent = $parent;
                $this->title = "New Page";

                $this->render();
            }
        );
        app("router")->add($newFieldRoute);



        $newSave = new Route;
        $newSave
            ->path("fields/new/:any/:all")
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
        app("router")->add($newSave);


        $saveFieldRoute = new Route;
        $saveFieldRoute
            ->path("fields/edit/:any")
            ->method("POST")
            ->parent("admin")
            ->callback(
            function ($name) {
                $page = app("fields")->get($name);
                $this->object = $page;
                $this->processSave();
            }
        );
        app("router")->add($saveFieldRoute);



    }


}
