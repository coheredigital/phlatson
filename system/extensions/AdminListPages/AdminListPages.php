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
    protected $pageListPage; // TODO this variable name suck and so does the way this works, you should be ashamed

    protected function setup()
    {

        $pageTree = new Route;
        $pageTree
            ->name("pages")
            ->path("pages")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );
        app("router")->add($pageTree);

        $pagesList = new Route;
        $pagesList
            ->path("pages/:all")
            ->parent("admin")
            ->callback(
                function ($url) {
                    $this->pageListPage = app("pages")->get($url);
                    $this->render();
                }
            );
        app("router")->add($pagesList);

    }


    protected function renderPageTree()
    {

        $markupPageList = app("extensions")->get("MarkupPageTree");

        $home = app("pages")->get("/");
        $markupPageList->rootPage = $home;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    protected function renderPageList(Page $page)
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
        if($this->pageListPage instanceof Page){
            $admin->output = $this->renderPageList($this->pageListPage);
        }
        else{
            $admin->output = $this->renderPageTree();
        }
        $admin->render();

    }

} 