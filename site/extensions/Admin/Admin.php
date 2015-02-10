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

        app("config")->styles->add("{$this->url}styles/admin.css");
        app("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        app("config")->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
        app("config")->scripts->add("{$this->url}scripts/main.js");
        app("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

        app("admin", $this); // register api variable

        // determine the admin route to use
        // check for a route named admin, then a config variable 'adminUrl' then default
        if (app("router")->get("admin")) {
            $this->route = app("router")->get("admin");
        }
        else {

            $this->route = new Route();
            $this->route
                ->name("admin")
                ->path("/admin")
                ->callback("Admin.render");

            app('router')->add($this->route);
        }

        // add the admin URL to the config urls variable for easy access/reference
        app("config")->urls->admin = $this->route->url;


        $logoutRoute = new Route;
        $logoutRoute->path("logout")
            ->name("logout")
            ->parent($this->route)
            ->callback(
                function () {
                    app("session")->logout();
                    app("session")->redirect($this->route->url);
                }
            );
        app('router')->add($logoutRoute);


        $login = new Route;
        $login
            ->name("login")
            ->path("login")
            ->parent($this->route);
        app('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->method("POST")
            ->parent($this->route)
            ->callback(
                function () {
                    if (app("session")->login(app("request")->post->username, app("request")->post->password)) {
                        app("session")->redirect(app("router")->get("admin")->url);
                    } else {
                        // add error message
                    }
                }
            );
        app('router')->add($loginSubmit);

    }


    public function render()
    {

        extract(app());

        if ($user->isGuest()) {

            if($request->url != $router->login->url) $session->redirect($router->login->url);
            // add the login stylesheet and load the login layout
            app("config")->styles->add("{$this->url}styles/login.css");
            include_once "login.php"; // TODO : include_once used because this was also getting called twice
        }
        else if($user->isLoggedIn()){
            if($request->url == $router->login->url || $request->url == $router->admin->url) $session->redirect($router->admin->url . "pages");
            if ($this->output) include_once "layout.php"; // TODO : this was added because render was getting called twice, look for better solution
        }

    }



    public function getSettings(){



        $form = app("extensions")->get("MarkupEditForm");

        $fieldset = app("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $field = new FieldtypeText();
        $field->label = "Main Color";
        $field->name = "color";

        $fieldset->add($field);


        $form->add($fieldset);

        return $form->render();

    }


} 