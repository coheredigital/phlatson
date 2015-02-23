<?php

class AdminListExtensions extends Extension
{


    protected $output;

    protected function setup()
    {

        $extensionRoute = new Route;
        $extensionRoute
            ->name("extensions")
            ->path("extensions")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        app('router')->add($extensionRoute);

    }


        protected function renderFieldsList()
        {

            $extensions = app("extensions")->all();
            $table = app("extensions")->get("MarkupTable");
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

            $admin = app("admin");
            $admin->title = "Extensions";
            $admin->output = $this->renderFieldsList();
            $admin->render();

        }

    }