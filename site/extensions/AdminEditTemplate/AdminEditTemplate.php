<?php

class AdminEditTemplate extends AdminEdit
{


    public function setup()
    {

        $templateEdit = new Route;
        $templateEdit
            ->path("/templates/edit/:any")
            ->parent(api("admin")->route)
            ->callback(
                function ($name) {
                    $this->object = api("templates")->get($name);
                    $this->template = $this->object->template;
                    $this->title = $this->object->title;
                    $this->render();
                }
            );

        $templateNew = new Route;
        $templateNew
            ->path("/templates/new/")
            ->parent(api("admin")->route)
            ->callback(
                function () {
                    $this->object = new Template();
                    $this->object->template = "template";
                    $this->object->parent = $parent;
                    $this->title = "New Page";
                    $this->render();
                }
            );

        $templateSave = new Route;
        $templateSave
            ->path("/templates/edit/:any")
            ->method("POST")
            ->parent(api("admin")->route)
            ->callback(
                function ($name) {
                    $page = api("templates")->get($name);
                    $this->object = $page;
                    $this->processSave();
                }
            );

        api('router')->add($templateEdit);
        api('router')->add($templateNew);
        api('router')->add($templateSave);

    }


}
