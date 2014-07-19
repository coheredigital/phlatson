<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminPanel extends Extension {

    public function __construct(){

        api::register("Admin", $this);


        Router::add("/admin", function(){
                $this->render();
            });

        Router::add("/admin/pages", function(){
                $this->render();
            });

        Router::add("/admin/login", function(){
                $this->render();
            });


    }

    public function render(){
        extract(api());
        $page = new Page(__DIR__ . "/pages/data.json");

        include __DIR__ . "/layouts/index.php";
    }

} 