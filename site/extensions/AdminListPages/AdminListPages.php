<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListPages extends Extension
{


    protected $output;

    protected function setup()
    {

        $pagesList = new Route;
        $pagesList
            ->name("pages")
            ->path("pages")
            ->parent(registry("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );
            registry("router")->add($pagesList);

    }


    protected function renderPagetree()
    {

        $markupPageList = registry("extensions")->get("MarkupPageList");

        $home = registry("pages")->get("/");
        $markupPageList->rootPage = $home;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    public function render()
    {

        $admin = registry("admin");
        $admin->title = "Pages";
        $admin->output = $this->renderPagetree();
        $admin->render();

    }

} 