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

        registry("config")->styles->add("{$this->url}styles/admin.css");
        registry("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        registry("config")->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
        registry("config")->scripts->add("{$this->url}scripts/main.js");
        registry("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

        registry("admin", $this); // register api variable

        // determine the admin route to use
        // check for a route named admin, then a config variable 'adminUrl' then default
        if (registry("router")->get("admin")) {
            $this->route = registry("router")->get("admin");
        }
        else {

            $this->route = new Route();
            $this->route
                ->name("admin")
                ->path("/admin")
                ->callback("Admin.render");

            registry('router')->add($this->route);
        }

        // add the admin URL to the config urls variable for easy access/reference
        registry("config")->urls->admin = $this->route->url;


        $logoutRoute = new Route;
        $logoutRoute->path("logout")
            ->name("logout")
            ->parent($this->route)
            ->callback(
                function () {
                    registry("session")->logout();
                    registry("session")->redirect($this->route->url);
                }
            );
        registry('router')->add($logoutRoute);


        $login = new Route;
        $login
            ->name("login")
            ->path("login")
            ->parent($this->route);
        registry('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->method("POST")
            ->parent($this->route)
            ->callback(
                function () {
                    if (registry("session")->login(registry("request")->post->username, registry("request")->post->password)) {
                        registry("session")->redirect(registry("router")->get("admin")->url);
                    }



                }
            );
        registry('router')->add($loginSubmit);

    }


    public function render()
    {

        extract(registry());

        if ($user->isGuest()) {

            if($request->url != $router->login->url) $session->redirect($router->login->url);
            // add the login stylesheet and load the login layout
            registry("config")->styles->add("{$this->url}styles/login.css");
            include "login.php";
        }
        else if($user->isLoggedIn()){
            if($request->url == $router->login->url || $request->url == $router->admin->url) $session->redirect($router->admin->url . "pages");
            if ($this->output) include "layout.php";
        }

    }



    public function getSettings(){



        $form = registry("extensions")->get("MarkupEditForm");

        $fieldset = registry("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $field = new FieldtypeText();
        $field->label = "Main Color";
        $field->name = "color";

        $fieldset->add($field);


        $form->add($fieldset);

        return $form->render();

    }


} 