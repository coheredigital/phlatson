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
        api("config")->urls->admin = $this->route->generate();


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
            ->path("login")
            ->parent($this->route)
            ->halt()
            ->callback(
                function () {
                    $this->renderLoginForm();
                }
            );
        api('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->name("login")
            ->method("POST")
            ->parent($this->route)
            ->halt()
            ->callback(
                function () {
                    if (api("session")->login(api("request")->post->username, api("request")->post->password)) {
                        api("session")->redirect(api("router")->get("admin")->generate());
                    } else {
                        // add error message

                    }
                    $this->renderLoginForm();
                }
            );
        api('router')->add($loginSubmit);

    }


    public function render()
    {

        extract(api());

        $adminLogin = $router->login->generate();

        if ($user->isGuest()) {
            if($request->url != $adminLogin) $session->redirect($adminLogin);
            $this->output = $this->renderLoginForm();
            include "login.php";
        }
        else if($user->isLoggedIn()){
            if($request->url == $adminLogin) $session->redirect($router->admin->generate());
            if ($this->output) include "layout.php"; // TODO : this was added because render was getting called twice, look for better solution
        }

    }


    public function renderLoginForm()
    {
        api("config")->styles->add("{$this->url}login.css");
        $this->title = "Login";

        $output .= "<div class='field'>
                    <label>Username</label>
                    <div class='ui left labeled icon input'>
                      <input name='username' type='text' placeholder='Username'>
                      <i class='user icon'></i>
                      <div class='ui corner label'>
                        <i class='icon asterisk'></i>
                      </div>
                    </div>
                  </div>";

        $output .= "<div class='field'>
                    <label>Password</label>
                    <div class='ui left labeled icon input'>
                      <input name='password' type='password' placeholder='Password'>
                      <i class='user icon'></i>
                      <div class='ui corner label'>
                        <i class='icon asterisk'></i>
                      </div>
                    </div>
                  </div>";

        $output .= "<button type='submit' class='ui button green fluid'>Login</button>";
        return "<form class='ui form segment form-login' method='POST'>{$output}</form>";

    }

} 