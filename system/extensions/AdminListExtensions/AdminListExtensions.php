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
            ->parent($this->api("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        $this->api('router')->add($this->route);

    }


}