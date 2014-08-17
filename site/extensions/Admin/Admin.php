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


        $this->route = api("router")->get("admin");
//        $this->route->name("admin");
////        $this->route->path("/" . api("config")->adminUrl);
//        $this->route->path("/");
//        $this->route->domain(api("config")->adminUrl .".xpages.dev");
//        $this->route->prependCallback(
//            function () {
//                $adminLogin = "http://admin.xpages.dev/login";
//                if (api("user")->isGuest() && api("request")->url != $adminLogin) {
//                    api("session")->redirect($adminLogin);
//                }
//            }
//        );


        $logoutRoute = new Route;
        $logoutRoute->path("logout");
        $logoutRoute->parent($this->route);
        $logoutRoute->callback(
            function () {
                api("session")->logout();
                api("session")->redirect(api("config")->urls->admin);
            }
        );


        $login = new Route;
        $login
            ->path("login")
            ->parent($this->route)
            ->halt()
            ->callback(
                function () {
                    $this->renderLogin();
                }
            );


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->name("login")
            ->method("POST")
            ->parent($this->route)
            ->callback(
                function () {
                    if (api("session")->login(api("request")->post->username, api("request")->post->password)) {
                        api("session")->redirect( api("router")->get("admin")->generate() );
                    } else {
                        // add error message

                    }
                    $this->renderLogin();
                }
            );

        // add the routes
        api('router')->add($this->route);
        api('router')->add($login);
        api('router')->add($loginSubmit);
        api('router')->add($logoutRoute);



    }

    public function run()
    {
//        extract(api());
//        include "layout.php";

//
//            $adminLogin = "http://admin.xpages.dev/login";
//            if (api("user")->isGuest() && api("request")->url != $adminLogin) {
//                api("session")->redirect($adminLogin);
//            }

    }

    public function render()
    {
        $adminLogin = "http://admin.xpages.dev/login";
        if (api("user")->isGuest() && api("request")->url != $adminLogin) {
            api("session")->redirect($adminLogin);
        }

        extract(api());
        include "layout.php";
    }




    public function renderLogin()
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
        $this->output = "<form class='ui form segment form-login' method='POST'>{$output}</form>";

        extract(api());
        include "login.php";

    }

} 