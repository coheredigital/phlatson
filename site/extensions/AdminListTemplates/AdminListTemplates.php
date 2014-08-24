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
        $templateList->path("templates");
        $templateList->parent(api("admin")->route);
        $templateList->callback(
            function () {
                $this->render();
            }
        );
        api('router')->add($templateList);

    }


    protected function renderTemplatesList()
    {

        $config = api("config");

        $fieldsList = api("templates")->all();
        $table = api("extensions")->get("MarkupTable");
        $table->setColumns(
            array(
                "Name" => "name",
                "Label" => "label",
                "fieldtype" => "fieldtype",
            )
        );

        foreach ($fieldsList as $item) {
            $table->addRow(
                array(
                    "name" => "<a href='{$config->urls->admin}templates/edit/{$item->name}' >{$item->name}</a>",
                    "label" => $item->label,
                    "fieldtype" => $item->type
                    // TODO : getting the formatted version of this causes an Exception to be thrown, look into this
                )
            );
        }

        $output = $table->render();

        $controls = "<div class='ui secondary pointing menu'>
                <a class='item' href='{$config->urls->root}{$config->adminUrl}/new'>New</a>
                <div class='right menu'>
                    <div class='item'>
                        <div class='ui icon input'>
                            <input type='text' placeholder='Filter...'>
                            <i class='search link icon'></i>
                        </div>
                    </div>
                </div>
            </div>";

        return "<div class='container'>{$controls}{$output}</div>";

    }

    public function render()
    {

        $admin = api("admin");
        $admin->title = "Templates";
        $admin->output = $this->renderTemplatesList();
        $admin->render();

    }

} 