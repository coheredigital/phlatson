<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListFields extends Extension
{


    protected $output;

    protected function setup()
    {

        $fieldsRoute = new Route;
        $fieldsRoute->path("fields");
        $fieldsRoute->parent(app("admin")->route);
        $fieldsRoute->callback(
            function () {
                $this->render();
            }
        );

        app('router')->add($fieldsRoute);

    }


    protected function renderFieldsList()
    {

        $config = app("config");

        $table = app("extensions")->get("MarkupTable");
        $table->setColumns(
            array(
                "Name" => "name",
                "Label" => "label",
                "Type" => "fieldtype",
            )
        );

        $fields = app("fields")->all();
        foreach ($fields as $item) {
            $table->addRow(
                array(
                    "name" => "<a href='{$config->urls->admin}fields/edit/{$item->name}' >{$item->name}</a>",
                    "label" => $item->title,
                    "fieldtype" => $item->type
                )
            );
        }

        $output = $table->render();


        $controls .= "<div class='form-actions'>";
        $controls .= "<div class='container'>";
        $controls .= "<a class='button' href='{$config->urls->admin}fields/new'>New</a>";
        $controls .= "</div>";

        return "<div class='container'>{$output}{$controls}</div>";

    }

    public function render()
    {

        $admin = app("admin");
        $admin->title = "Fields";
        $admin->output = $this->renderFieldsList();
        $admin->render();

    }

} 