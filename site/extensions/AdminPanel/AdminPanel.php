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

        Router::add($this->name, "/admin/<string:name>", function($name = null){
                $this->render($name);
            });

    }


    protected function getPage($name = null)
    {
        $input = api("input");

        $page = new Page();

        if(!is_null($name)){
            $page->layoutFile = __DIR__ . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $name . ".php";
        }
        else{
            $page->layoutFile = __DIR__ . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "pagetree" . ".php";
        }

        return $page;

    }


    public function render($name = null)
    {
        extract(api());

        $page = $this->getPage($name);

        include __DIR__ . "/layouts/index.php";
    }

} 