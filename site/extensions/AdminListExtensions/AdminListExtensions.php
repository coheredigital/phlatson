<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListExtensions extends Extension
{


    protected $output;

    protected function setup()
    {

        $extensionRoute = new Route;
        $extensionRoute
            ->name("extensions")
            ->path("extensions")
            ->parent(registry("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        registry('router')->add($extensionRoute);

    }


        protected function renderFieldsList()
        {

            $extensions = registry("extensions")->all();
            $table = registry("extensions")->get("MarkupTable");
            $table->setColumns(
                array(
                    "Name" => "name",
                    "Type" => "type"
                )
            );

            foreach ($extensions as $item) {
                $table->addRow(
                    array(
                        "name" => $item->name,
                        "type" => $item->type
                    )
                );
            }

            $output = $table->render();

            return "<div class='container'>{$output}</div>";

        }

        public
        function render()
        {

            $admin = registry("admin");
            $admin->title = "Extensions";
            $admin->output = $this->renderFieldsList();
            $admin->render();

        }

    }