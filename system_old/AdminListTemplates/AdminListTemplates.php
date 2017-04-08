<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListTemplates extends AdminList
{

    public $title = "Templates";
    protected $objectName = "templates";

    protected function setup()
    {

        $this->route = new Route;
        $this->route
            ->name("templates")
            ->path("templates")
            ->parent($this->api("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );
        $this->api('router')->add($this->route);

    }


} 