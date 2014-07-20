<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminPanel extends Extension {

    protected function setup(){

        Router::add("/admin", function(){
                $this->render();
            });

    }

    public function render(){
        extract(api());
        $page = new Page(__DIR__ . "/pages/data.json");
        include __DIR__ . "/layouts/index.php";
    }

} 