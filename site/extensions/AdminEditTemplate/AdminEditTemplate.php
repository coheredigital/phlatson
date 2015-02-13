<?php

class AdminEditTemplate extends AdminEdit
{


    public function setup()
    {

        $templateEdit = new Route;
        $templateEdit
            ->path("/templates/edit/:any")
            ->parent(registry("admin")->route)
            ->callback(
                function ($name) {
                    $this->object = registry("templates")->get($name);
                    $this->template = $this->object->template;
                    $this->title = $this->object->title;
                    $this->render();
                }
            );

        $templateNew = new Route;
        $templateNew
            ->path("/templates/new/")
            ->parent(registry("admin")->route)
            ->callback(
                function () {
                    $this->object = new Template();
                    $this->object->template = "template";
                    $this->title = "New Template";
                    $this->render();
                }
            );

        $templateNewSave = new Route;
        $templateNewSave
            ->path("/templates/new/")
            ->method("POST")
            ->parent(registry("admin")->route)
            ->callback(
                function () {
                    $this->processSave();
                }
            );
        $templateSave = new Route;
        $templateSave
            ->path("/templates/edit/:any")
            ->method("POST")
            ->parent(registry("admin")->route)
            ->callback(
                function ($name) {
                    $page = registry("templates")->get($name);
                    $this->object = $page;
                    $this->processSave();
                }
            );

        registry('router')->add($templateEdit);
        registry('router')->add($templateNew);
        registry('router')->add($templateNewSave);
        registry('router')->add($templateSave);

    }


}
