<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminExtensionsList extends Extension
{


    protected $output;

    protected function setup()
    {

        $extensionRoute = new Route;
        $extensionRoute
            ->path("extensions")
            ->parent(api("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        api('router')->add($extensionRoute);

    }


        protected function renderFieldsList()
        {

            $extensions = api("extensions")->all();
            $table = api("extensions")->get("MarkupTable");
            $table->setColumns(
                array(
                    "Name" => "name",
                    "Label" => "label",
                    "Type" => "type",
                )
            );

            foreach ($extensions as $item) {
                $table->addRow(
                    array(
                        "name" => $item->name,
                        "label" => $item->label,
                        "type" => $item->type
                    )
                );
            }

            $output = $table->render();

            $controls = "<div class='ui secondary pointing menu'>
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

        public
        function render()
        {

            $admin = api("admin");
            $admin->title = "Extensions";
            $admin->output = $this->renderFieldsList();
            $admin->render();

        }

    }