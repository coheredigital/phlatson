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
        api("admin", $this); // register api variable
    }


    public function render()
    {
        extract(api());
        include __DIR__ . "/layouts/index.php";
    }

} 