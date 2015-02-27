<?php

class AdminListExtensions extends AdminList
{

    public $title = "Extensions";
    protected $objectName = "extensions";


    protected function setup()
    {

        $this->route = new Route;
        $this->route
            ->name("extensions")
            ->path("extensions")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        app('router')->add($this->route);

    }


}