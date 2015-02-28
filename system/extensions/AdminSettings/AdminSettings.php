<?php

class AdminSettings extends Extension
{

    protected $title = "Settings";
    protected $sections = [
        "name"=>"default",
        "title"=>"Site"
    ];

    protected function setup()
    {

        $this->route = new Route([
            "name" => "settings",
            "path" => "settings",
            "parent" => "admin",
            "callback" => function () {
                $this->render();
            }
        ]);

        app('router')->add($this->route);

    }


    public function render()
    {
        $admin = app("admin");
        $admin->title = $this->title;
        $admin->render();

    }

} 