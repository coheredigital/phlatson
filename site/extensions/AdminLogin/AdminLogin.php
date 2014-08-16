<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminLogin extends Admin
{


    protected function setup()
    {
        $config = api("config");

        $login = new Route;
        $login->path("/$config->adminUrl/login");
        $login->callback(
            function () {
                $this->render();
            }
        );


        $loginSubmit = new Route;
        $loginSubmit->path("/{$config->adminUrl}/login");
        $loginSubmit->method("POST");
        $loginSubmit->callback(
            function () {
                $api = api();
                if (count(api("request")->post)) {
                    if (api("session")->login(api("request")->post->username, api("request")->post->password)) {
                        api("session")->redirect(api("config")->urls->admin);
                    } else {
                        // add error message

                    }
                }

                $this->render();
            }
        );

        // add the routes
        api('router')->add($login);
        api('router')->add($loginSubmit);

    }


    protected function renderForm()
    {


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

    public function render()
    {
        api("config")->styles->add("{$this->url}login.css");
        $this->title = "Login";
        $this->output = $this->renderForm();

        extract(api());
        include "layout.php";

    }

} 