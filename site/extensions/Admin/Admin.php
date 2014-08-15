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

        // default admin scripts and styles
        $config->styles->add("{$this->url}styles/adminTheme.css");
        $config->styles->add("{$this->url}styles/semantic.min.css");
        $config->styles->append("{$this->url}styles/font-awesome-4.1.0/css/font-awesome.css");
        $config->scripts->prepend("{$this->url}scripts/semantic.min.js");
        $config->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");
        $config->scripts->add("{$this->url}scripts/jquery-sortable.js");
        $config->scripts->add("{$this->url}scripts/init.js");

        api("admin", $this); // register api variable



        $adminRoute = new Route;
        $adminRoute->name("admin");
        $adminRoute->url("/{$config->adminUrl}");
        $adminRoute->prependCallback(function(){
                if( api("user")->isGuest() ){
                    api("session")->redirect(  api("config")->urls->root . api("config")->adminUrl . "/login");
                }
            });

        api('router')->add( $adminRoute );



        $logoutRoute = new Route;
        $logoutRoute->url("/{$config->adminUrl}/logout");
        $logoutRoute->callback(function(){
                api("session")->logout();
                api("session")->redirect( api("config")->urls->admin );
            });

        
        api('router')->add( $logoutRoute );

    }

    public function render()
    {
        extract(api());
        include "layout.php";
    }

} 