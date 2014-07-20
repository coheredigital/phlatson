<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminPanel extends Extension {

    protected function setup()
    {

        Router::add("/admin", function(){
                $this->render();
            });

    }


    protected function getPage()
    {
        $input = api("input");

        $page = new Page();

        switch ( $input->url ){

            case "/admin":
                $page->title = "Content";
                $page->layout = "pagetree";
                break;
            case "/admin/login":
                $page->title = "Login";
                $page->layout = "login";
                break;
            case "/admin/settings":
                $page->title = "Settings";
                $page->layout = "settings";
                break;
        }

        return $page;

    }

    public function render()
    {
        extract(api());
        $page = $this->getPage();
        include __DIR__ . "/layouts/index.php";
    }

} 