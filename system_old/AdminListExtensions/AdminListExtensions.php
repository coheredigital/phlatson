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

    protected function renderList()
    {

        $objectCollection = $this->api($this->objectName)->all();

        $table = $this->api("extensions")->get("MarkupTable");

        $table->setColumns([
            "Title" => "title",
            "Name" => "name",
            "Autoload" => "autoload"
        ]);

        foreach ($objectCollection as $object) {

            if( $object->isSystem() ) continue;
            
            $table->addRow(
                array(
                    "title" => $object->isEditable() ? "<a href='{$object->urlEdit}'>{$object->title}</a>" : $object->title,
                    "name" => $object->name,
                    "autoload" => $object->autoload ? 'YES' : ''
                )
            );
        }

        $output = $table->render();

        return "<div class='container'>{$output}</div>";
    }
}
