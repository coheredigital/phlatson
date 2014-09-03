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

        api('router')->add(
            new Route([
                "path" => "settings",
                "parent" => api("admin")->route,
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




        $extensions = api("extensions")->all();
        $extensions->filter([
                "configurable" => true
            ]);

        $admin = api("admin");
        $admin->title = "Settings";

        foreach($extensions as $extension) {

            if(!count($extension->fields)) continue;

            $form = api("extensions")->get("MarkupEditForm");

            $tabNames["$extension->name"] = $extension->title;
            foreach ( $extension->fields as $field ) {

                $fieldtypeName = $field["fieldtype"];
                $fieldtype = api("extensions")->get($fieldtypeName);
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