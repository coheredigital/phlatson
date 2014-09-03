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

        api("config")->styles->add("{$this->url}styles/admin.css");
        api("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        api("config")->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
        api("config")->scripts->add("{$this->url}scripts/main.js");
        api("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

        api("admin", $this); // register api variable

        // determine the admin route to use
        // check for a route named admin, then a config variable 'adminUrl' then default
        if (api("router")->get("admin")) {
            $this->route = api("router")->get("admin");
        }
        else {

            $adminUrl = api("config")->adminUrl ? api("config")->adminUrl : "admin";
            $adminUrl = "/" . trim( $adminUrl , "/");

            $this->route->name("admin");
            $this->route->path($adminUrl);
            $this->route->callback("Admin:render");

            api('router')->add($this->route);
        }

        // add the admin URL to the config urls variable for easy access/reference
        api("config")->urls->admin = $this->route->url;


        $logoutRoute = new Route;
        $logoutRoute->path("logout");
        $logoutRoute->name("logout");
        $logoutRoute->parent($this->route);
        $logoutRoute->callback(
            function () {
                api("session")->logout();
                api("session")->redirect(api("config")->urls->admin);
            }
        );
        api('router')->add($logoutRoute);


        $login = new Route;
        $login
            ->name("login")
            ->path("login")
            ->parent($this->route);
        api('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->method("POST")
            ->parent($this->route)
            ->callback(
                function () {
                    if (api("session")->login(api("request")->post->username, api("request")->post->password)) {
                        api("session")->redirect(api("router")->get("admin")->url);
                    } else {
                        // add error message
                    }
                }
            );
        api('router')->add($loginSubmit);

    }


    public function render()
    {

        extract(api());

        if ($user->isGuest()) {

            if($request->url != $router->login->url) $session->redirect($router->login->url);
            // add the login stylesheet and load the login layout
            api("config")->styles->add("{$this->url}styles/login.css");
            include_once "login.php"; // TODO : include_once used because this was also getting called twice
        }
        else if($user->isLoggedIn()){
            if($request->url == $router->login->url || $request->url == $router->admin->url) $session->redirect($router->admin->url . "pages");
            if ($this->output) include_once "layout.php"; // TODO : this was added because render was getting called twice, look for better solution
        }

    }



    public function getSettings(){



        $form = api("extensions")->get("MarkupEditForm");

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $field = new FieldtypeText();
        $field->label = "Main Color";
        $field->name = "color";

        $fieldset->add($field);


        $form->add($fieldset);

        return $form->render();

    }


} 