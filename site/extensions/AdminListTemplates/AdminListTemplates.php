<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListTemplates extends Extension
{


    protected function setup()
    {

        $templateList = new Route;
        $templateList
            ->name("templates")
            ->path("templates")
            ->parent(registry("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );
            registry('router')->add($templateList);

    }


    protected function renderTemplatesList()
    {

        $config = registry("config");

        $fieldsList = registry("templates")->all();
        $table = registry("extensions")->get("MarkupTable");
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

        $controls .= "<div class='form-actions'>";
        $controls .= "<div class='container'>";
        $controls .= "<a class='button' href='{$config->urls->admin}templates/new'>New</a>";
        $controls .= "</div>";

        return "<div class='container'>{$output}{$controls}</div>";

    }

    public function render()
    {

        $admin = registry("admin");
        $admin->title = "Templates";
        $admin->output = $this->renderTemplatesList();
        $admin->render();

    }

} 