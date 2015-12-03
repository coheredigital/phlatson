<?php

class AdminEditTemplate extends AdminEdit
{


    public function setup()
    {

        $templateEdit = new Route;
        $templateEdit
            ->path("/templates/edit/:any")
            ->parent($this->api("admin")->route)
            ->callback(
                function ($name) {
                    $this->object = $this->api("templates")->get($name);
                    $this->template = $this->object->template;
                    $this->title = $this->object->title;
                    $this->render();
                }
            );

        $templateNew = new Route;
        $templateNew
            ->path("/templates/new/")
            ->parent($this->api("admin")->route)
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
            ->parent($this->api("admin")->route)
            ->callback(
                function () {
                    $this->processSave();
                }
            );
        $templateSave = new Route;
        $templateSave
            ->path("/templates/edit/:any")
            ->method("POST")
            ->parent($this->api("admin")->route)
            ->callback(
                function ($name) {
                    $page = $this->api("templates")->get($name);
                    $this->object = $page;
                    $this->processSave();
                }
            );

        $this->api('router')->add($templateEdit);
        $this->api('router')->add($templateNew);
        $this->api('router')->add($templateNewSave);
        $this->api('router')->add($templateSave);

    }


}
