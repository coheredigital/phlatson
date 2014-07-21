<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminLogin extends Admin {



    protected function setup()
    {
        $config = api("config");
        Router::get( "/{$config->adminUrl}/login" , function(){
                $this->render();
            });
    }


    protected function renderForm(){

//        if ($user->isLoggedin()) {
//            $session->redirect($config->urls->root . $config->adminUrl);
//        }
//
//        if (count($input->post)) {
//            $session->login($input->post->username, $input->post->password);
//            $session->redirect($config->urls->root . $config->adminUrl . "/login");
//        }



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

        $this->title = "Login";
        $this->output = $this->renderForm();

        extract(api());
        include "layout.php";

    }

} 