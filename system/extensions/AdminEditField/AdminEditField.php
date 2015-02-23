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
