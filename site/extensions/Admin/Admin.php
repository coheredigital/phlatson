<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class Admin extends Extension
{

    public $title;
    public $output;
    public $route;

    protected function setup()
    {

        // default admin scripts and styles
        api("config")->styles->add("{$this->url}styles/adminTheme.css");
        api("config")->styles->add("{$this->url}styles/semantic.min.css");
        api("config")->styles->append("{$this->url}styles/font-awesome-4.1.0/css/font-awesome.css");
        api("config")->scripts->prepend("{$this->url}scripts/semantic.min.js");
        api("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");
        api("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        api("config")->scripts->add("{$this->url}scripts/init.js");

        api("admin", $this); // register api variable


        $this->route = new Route;
        $this->route->name("admin");
        $this->route->path("/" . api("config")->adminUrl);
        $this->route->prependCallback(
            function () {
                if (api("user")->isGuest()) {
                    api("session")->redirect(api("config")->urls->root . api("config")->adminUrl . "/login");
                }
            }
        );

        api('router')->add($this->route);


        $logoutRoute = new Route;
        $logoutRoute->path("logout");
        $logoutRoute->callback(
            function () {
                api("session")->logout();
                api("session")->redirect(api("config")->urls->admin);
            }
        );
        $logoutRoute->parent($this->route);


        api('router')->add($logoutRoute);

    }

    public function render()
    {
        extract(api());
        include "layout.php";
    }

} 