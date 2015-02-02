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
        $pagesList->path("pages");
        $pagesList->parent(app("admin")->route);
        $pagesList->callback(
            function () {
                $this->render();
            }
        );
//        api("router")->add($pagesList);

    }


    protected function renderPagetree()
    {

        $markupPageList = app("extensions")->get("MarkupPageList");

        $home = app("pages")->get("/");
        $markupPageList->rootPage = $home;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    public function render()
    {

        $admin = app("admin");
        $admin->title = "Pages";
        $admin->output = $this->renderPagetree();
        $admin->render();

    }

} 