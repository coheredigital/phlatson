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

    }

} 