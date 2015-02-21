<?php

class AdminSettings extends Extension
{

    protected function setup()
    {

        app('router')->add(
            new Route([
                "path" => "settings",
                "parent" => app("admin")->route,
                "callback" => function () {
                        $this->render();
                    }
            ])
        );
    }


    public function render()
    {

        $tabNames = [];
        $tabs = [];




        $extensions = app("extensions")->all();
        $extensions->filter([
                "configurable" => true
            ]);

        $admin = app("admin");
        $admin->title = "Settings";

        foreach($extensions as $extension) {

            if(!count($extension->fields)) continue;

            $form = app("extensions")->get("MarkupEditForm");

            $tabNames["$extension->name"] = $extension->title;
            foreach ( $extension->fields as $field ) {

                $fieldtypeName = $field["fieldtype"];
                $fieldtype = app("extensions")->get($fieldtypeName);
                $fieldtype->settings($field["settings"]);
                $fieldtype->label = $field["label"];
                $fieldtype->value = $field["default"];
                $fieldtype->attribute("name", $field["name"]);

                $form->add($fieldtype);
            }

            $tabs["$extension->name"] = $form;

        }


        foreach($tabs as $tab) {

            $admin->output .= $tab->render();

        }


        $admin->render();

    }

} 