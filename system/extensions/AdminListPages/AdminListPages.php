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
    protected $rootPage; // TODO this variable name suck and so does the way this works, you should be ashamed

    public $subnav;

    protected function setup()
    {

        $this->subnav = new ObjectCollection();

        $this->route = new Route;
        $this->route
            ->name("pages")
            ->path("pages")
            ->parent(app("admin")->route)
            ->callback(
                function () {
                    $this->rootPage = app("pages")->get("/");
                    $this->render();
                }
            );

        app("router")->add($this->route);

        $subpageRoute = new Route;
        $subpageRoute
            ->path("pages/:all")
            ->parent("admin")
            ->callback(
                function ($url) {
                    $this->rootPage = app("pages")->get($url);
                    $this->render();
                }
            );
        app("router")->add($subpageRoute);

    }


    protected function renderPageTree()
    {

        $markupPageList = app("extensions")->get("MarkupPageTree");
        $markupPageList->rootPage = $this->rootPage;
        $markupPageList->admin = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    protected function renderPageList()
    {

        $markupPageList = app("extensions")->get("MarkupPageList");
        $markupPageList->rootPage = $this->rootPage;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }


    protected function renderSubnav()
    {

        if (!count($this->subnav)) return false;

        $output = "";

        // add pages roote items first
        $output .= "<a class='item' href='{$this->route->url}'>Pages</a>";

        foreach($this->subnav as $page){
            $output .= "<a class='item' href='{$this->route->url}{$page->uri}'>$page->title</a>";
        }
        $output = "<div class='page-tree-subnav menu'><div class='container'>$output</div></div>";
        return $output;
    }

    public function render()
    {

        $admin = app("admin");
        $admin->title = "Pages";

        $template = $this->rootPage->template;



        if (is_array($template->view) && $template->view["type"] == "list") {
            $admin->output .= $this->renderPageList();
        } else {
            $admin->output .= $this->renderPageTree();
        }

        $admin->output = $this->renderSubnav() . $admin->output;

        $admin->render();

    }

} 