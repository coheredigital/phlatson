<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminSettings extends Extension
{

    protected function setup()
    {

        registry('router')->add(
            new Route([
                "path" => "settings",
                "parent" => registry("admin")->route,
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




        $extensions = registry("extensions")->all();
        $extensions->filter([
                "configurable" => true
            ]);

        $admin = registry("admin");
        $admin->title = "Settings";

        foreach($extensions as $extension) {

            if(!count($extension->fields)) continue;

            $form = registry("extensions")->get("MarkupEditForm");

            $tabNames["$extension->name"] = $extension->title;
            foreach ( $extension->fields as $field ) {

                $fieldtypeName = $field["fieldtype"];
                $fieldtype = registry("extensions")->get($fieldtypeName);
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