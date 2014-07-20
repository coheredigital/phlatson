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

        $adminUrl = trim($this->adminUrl, "/");
        $adminUrl = "/{$adminUrl}";

        api("admin", $this);

        Router::get( "$adminUrl" , function(){
                $this->render("pagetree");
            });


    }




    public function render($name = "pagetree", $extension = false)
    {
        extract(api());

        $page = new Page();

        if($extension) {
            $page = $name;
            $page->extension = true;
        }
        else{
            $page->layoutFile = __DIR__ . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $name . ".php";
        }


        include __DIR__ . "/layouts/index.php";
    }

} 