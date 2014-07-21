<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminSettings extends Extension {

    protected function setup()
    {
        $config = api("config");
        Router::get( "/{$config->adminUrl}/settings" , function(){
                $this->render();
            });
    }


    public function render()
    {

        $admin = api("admin");
        $admin->output = "<div class='container'><h4>Settings</h4><div class='ui list'>{$output->main}</div></div>";
        $admin->render();

    }

} 