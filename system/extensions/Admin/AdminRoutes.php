<?php

class AdminRoutes extends Admin
{


    public function setup(){

        if ($this->api("router")->get("admin")) {
            $this->api("admin")->route = $this->api("router")->get("admin");
        } else throw new FlatbedException("Admin route missing from Site.json configuration file.");

        // add the admin URL to the config urls variable for easy access/reference
        $this->api("config")->urls->admin = $this->route->url;


        $logoutRoute = new Route;
        $logoutRoute->path("logout")
            ->name("logout")
            ->parent($this->route)
            ->callback(
                function () {
                    $this->api("session")->logout();
                    $this->api("router")->redirect($this->api("router")->get("login"), false);
                }
            );
        $this->api('router')->add($logoutRoute);


        $login = new Route;
        $login
            ->name("login")
            ->path("login")
            ->parent("admin")
            ->callback(function () {
                //  add the login stylesheet and load the login layout
                $this->api("config")->styles->add("{$this->url}styles/login.css");
                include "login.php";

            });
        $this->api('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->method("POST")
            ->parent("admin")
            ->callback(
                function () {
                    if ($this->api("session")->login($this->api("request")->post->username, $this->api("request")->post->password)) {
                        $this->api("router")->redirect($this->api("router")->get("admin")->url, false);
                    }
                }
            );
        $this->api('router')->add($loginSubmit);

    }

}