<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListFields extends AdminList
{

    public $title = "Fields";
    protected $objectName = "fields";

    protected function setup()
    {

        $this->route = new Route;
        $this->route
            ->name("fields")
            ->path("fields")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        app('router')->add($this->route);

    }




} 