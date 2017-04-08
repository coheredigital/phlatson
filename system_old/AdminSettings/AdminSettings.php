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


    protected function renderList()
    {

        // $objectCollection = $this->api($this->objectName)->all();
        $table = $this->api("extensions")->get("MarkupTable");

        $configurableExtensions = $this->api("extensions")->all();

        $table->setColumns([
            "title"
        ]);

        foreach ($configurableExtensions as $extension) {
            if ($extension instanceof Extension && $extension->configurable) continue;
            $table->addRow(
                array(
                    "title" => $object->title,
                )
            );
        }

        $output = $table->render();

        return "<div class='container'>{$output}</div>";

    }

    public function render()
    {
        $output = $this->renderList();
        return "";
    }

} 