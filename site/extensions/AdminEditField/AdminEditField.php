<?php

class AdminEditField extends AdminEdit
{


    public static function getInfo()
    {
        return array(
            'title' => 'ColorPicker',
            'version' => 104,
            'summary' => 'Choose your colors the easy way.',
            'href' => 'http://processwire.com/talk/topic/865-module-colorpicker/page__gopid__7340#entry7340',
            'requires' => array("FieldtypeColorPicker")
        );
    }


    public function setup()
    {

        $fieldRoute = new Route;
        $fieldRoute->path("fields/edit/:any");
        $fieldRoute->parent(api("admin")->route);
        $fieldRoute->callback(
            function ($name) {
                $this->object = api("fields")->get($name);

                $this->title = "Edit Field";

                $this->render();
            }
        );

        $newFieldRoute = new Route;
        $newFieldRoute->path("fields/new/");
        $newFieldRoute->parent(api("admin")->route);
        $newFieldRoute->callback(
            function () {
                $this->object = new Field();
                $this->object->template = "field";
                $this->object->parent = $parent;
                $this->title = "New Page";

                $this->render();
            }
        );

        $saveFieldRoute = new Route;
        $saveFieldRoute->path("fields/edit/:any");
        $saveFieldRoute->method("POST");
        $saveFieldRoute->callback(
            function ($name) {
                $page = api("fields")->get($name);
                $this->object = $page;
                $this->processSave();
            }
        );
        $saveFieldRoute->parent(api("admin")->route);

        api('router')->add($fieldRoute);
        api('router')->add($newFieldRoute);
        api('router')->add($saveFieldRoute);


    }


}
