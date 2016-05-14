<?php

class AdminSettings extends Admin implements AdminPage
{

    public $title = "Settings";
    protected $sections = [
        "name"=>"default",
        "title"=>"Site"
    ];

    protected function setup()
    {

        $this->route = new Route;
        $this->route
            ->name("settings")
            ->path("settings")
            ->parent("admin")
            ->callback(
                function () {
                    $this->api("admin")->title = "Settings";
                    $this->api("admin")->page = $this;
                    $this->api("admin")->render();
                }
            );
        $this->api('router')->add($this->route);

    }



    public function render()
    {
        return "";
    }

} 