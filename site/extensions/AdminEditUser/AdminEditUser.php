<?php

class AdminEditUser extends AdminEdit
{



    public function setup()
    {

        $fieldRoute = new Route;
        $fieldRoute
            ->path("users/edit/:any")
            ->parent("admin")
            ->callback(
            function ($name) {
                $this->object = registry("users")->get($name);
                $this->title = "Edit User";
                $this->render();
            }
        );
        registry("router")->add($fieldRoute);


        $newFieldRoute = new Route;
        $newFieldRoute
            ->path("users/new/")
            ->parent("admin")
            ->callback(
            function () {
                $this->object = new Field();
                $this->object->template = "field";
                $this->object->parent = $parent;
                $this->title = "New User";

                $this->render();
            }
        );
        registry("router")->add($newFieldRoute);

        $saveFieldRoute = new Route;
        $saveFieldRoute
            ->path("users/edit/:any")
            ->method("POST")
            ->parent("admin")
            ->callback(
            function ($name) {
                $page = registry("fields")->get($name);
                $this->object = $page;
                $this->processSave();
            }
        );
        registry("router")->add($saveFieldRoute);



    }


}
