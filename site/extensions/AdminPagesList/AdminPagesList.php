<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminPagesList extends Extension {


    protected $output;

    protected function setup()
    {


        $config = api("config");

        api('router')->add(
            new Route( "/" . api("config")->adminUrl , function(){

                if( api("user")->isGuest() ){
                    api("session")->redirect(  api("config")->urls->root . api("config")->adminUrl . "/login");
                }

                $this->render();
            })
        );

    }


    protected function renderPagetree()
    {

        $markupPageList = api("extensions")->get("MarkupPageList");

        $home = api("pages")->get("/");
        $markupPageList->rootPage = $home;
        $markupPageList->adminPanel = $this;

        return "<div class='container'>" . $markupPageList->render() . "</div>";

    }

    public function render()
    {

        $admin = api("admin");
        $admin->title = "Pages";
        $admin->output = $this->renderPagetree();
        $admin->render();

    }

} 