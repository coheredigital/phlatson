<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class Admin extends Extension {


    public $title;
    public $output;


    protected function setup()
    {
        $config = api("config");
        api("admin", $this); // register api variable

        api('router')->add(
            new Route( "/$config->adminUrl/logout" , function(){
                api("session")->logout();
                api("session")->redirect("{$config->urls->root}{$config->adminUrl}");
            })
        );


    }

    public function render()
    {
        extract(api());
        include "layout.php";
    }

} 