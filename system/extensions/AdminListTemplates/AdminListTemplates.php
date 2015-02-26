<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListTemplates extends Extension
{

    protected $title = "Templates";

    protected function setup()
    {

        $templateList = new Route;
        $templateList
            ->name("templates")
            ->path("templates")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );
        app('router')->add($templateList);

    }


    protected function renderList()
    {

        $config = app("config");

        $fieldsList = app("templates")->all();
        $table = app("extensions")->get("MarkupTable");
        $table->setColumns(
            array(
                "Name" => "name",
                "Label" => "label",
                "Field Count" => "count",
            )
        );

        foreach ($fieldsList as $item) {
            $table->addRow(
                array(
                    "name" => "<a href='{$config->urls->admin}templates/edit/{$item->name}' >{$item->name}</a>",
                    "label" => $item->label,
                    "count" => count($item->fields)
                    // TODO : getting the formatted version of this causes an Exception to be thrown, look into this
                )
            );
        }

        $output = $table->render();

        return "<div class='container'>{$output}</div>";

    }

    protected function renderControls()
    {
        $output = "<div class='form-actions'>";
        $output .= "<a class='button' href='{$config->urls->admin}templates/new'>New</a>";
        $output .= "</div>";

        return $output;
    }

    protected function renderHeading()
    {


        $output = "<div id='title'>";
        $output .= "<div class='container'>";
        $output .= "<div class='main-title'>{$this->title}</div>";
        $output .= $this->renderControls();
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    public function render()
    {

        $admin = app("admin");
        $admin->title = $this->title;
        $heading = $this->renderHeading();
        $list = $this->renderList();
        $admin->output = "{$heading}{$list}";
        $admin->render();
    }

} 