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

            $admin = api("admin");
            $admin->title = "Extensions";
            $admin->output = $this->renderFieldsList();
            $admin->render();

        }

    }