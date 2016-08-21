<?php

class AdminListPages extends Extension implements AdminPage
{

    protected $output;
    protected $rootPage; // TODO this variable name suck and so does the way this works, you should be ashamed

    public $subnav;

    protected function setup()
    {
        $this->api("config")->scripts->add("{$this->url}{$this->className}.js");



        $this->subnav = new ObjectCollection();



        $this->route = new Route;
        $this->route
            ->name("pages")
            ->path("pages")
            ->parent("admin")
            ->callback(
                function () {
                    $this->rootPage = $this->api("pages")->get("/");
                    $this->api("admin")->title = "Pages";
                    $this->api("admin")->page = $this;
                    $this->api("admin")->render();
                }
            );

        $this->api("router")->add($this->route);

        $subpageRoute = new Route;
        $subpageRoute
            ->path("pages/:all")
            ->parent("admin")
            ->callback(
                function ($uri) {
                    $admin =  $this->api("admin");
                    $admin->title = "Pages";
                    $admin->page = $this;
                    $admin->render();
                }
            );
        $this->api("router")->add($subpageRoute);

    }


    protected function renderPageTree()
    {   
        


        if ($this->api("request")->get->root) {
            $rootPage = $this->api("pages")->get($this->api("request")->get->root);
        }
        else {
            $rootPage = $this->api("pages")->get("/");
        }

        $markupPageList = $this->api("extensions")->get("MarkupPageTree");
        $markupPageList->rootPage = $rootPage;
        $markupPageList->admin = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    protected function renderPageList()
    {

        $markupPageList = $this->api("extensions")->get("MarkupPageList");
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

        if (is_array($this->rootPage->template->view) && $this->rootPage->template->view["type"] == "list") {
            $output .= $this->renderPageList();
        } else {
            $output .= $this->renderPageTree();
        }
        return $output = $this->renderSubnav() . $output;
    }

} 