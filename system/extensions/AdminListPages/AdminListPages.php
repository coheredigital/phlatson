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

    protected function setup()
    {

        $pageTree = new Route;
        $pageTree
            ->name("pages")
            ->path("pages:all")
            ->parent(app("admin")->route)
            ->callback(
                function ($url) {
                    $this->rootPage = app("pages")->get($url);
                    $this->render();
                }
            );
        app("router")->add($pageTree);

    }


    protected function renderPageTree()
    {

        $markupPageList = app("extensions")->get("MarkupPageTree");
        $markupPageList->rootPage = $this->rootPage;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    protected function renderPageList(Page $page)
    {

        $markupPageList = app("extensions")->get("MarkupPageList");
        $markupPageList->rootPage = $this->rootPage;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    public function render()
    {

        $admin = app("admin");
        $admin->title = "Pages";



        if($this->rootPage->template->view){
            $admin->output = $this->renderPageList($this->rootPage);
        }
        else{
            $admin->output = $this->renderPageTree($this->rootPage);
        }
        $admin->render();

    }

} 