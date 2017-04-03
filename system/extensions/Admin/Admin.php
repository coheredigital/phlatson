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
    public $messages = [];

    protected $page;

    /* ObjectCollection used to create menus */
    public $children;

    protected function setup()
    {


        $this->api("admin", $this); // register api variable


        $this->children = new ObjectCollection();

        // default admin scripts and styles
        $this->api("config")->styles->add("{$this->url}styles/admin.css");
        $this->api("config")->styles->add("https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css");
        $this->api("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        $this->api("config")->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
        $this->api("config")->scripts->add("{$this->url}scripts/main.js");
        $this->api("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

        $this->setupRoutes();

    }


    protected function setupRoutes(){


        if ($this->api("router")->get("admin")) {
            $this->route = $this->api("router")->get("admin");
        } else {
            $route = new Route;
            $route->path("admin")
                ->name("admin")
                ->before("Admin.authorize")
                ->before("Admin.gotoAdminPage");
            $this->api('router')->add($route);
            $this->route = $route;
        }

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
                    $session = $this->api("session");
                    $router = $this->api("router");

                    try {
                        if ($session->login( $this->api("request")->post->username , $this->api("request")->post->password )) {
                            $router->redirect( $router->get("admin")->url, false );
                        }
                    } catch (FlatbedException $e) {
                        $session->flash("loginError", $e->getMessage());
                        $router->redirect( $router->get("login")->url, false );
                    }
                }
            );
        $this->api('router')->add($loginSubmit);

    }

    /**
     * run before all children of the the "admin" route to
     * ensure user is logged in before taking action
     *
     */
    public function authorize()
    {
        if ( !$this->api("user")->isGuest() )  return;

        $loginRoute = $this->api("router")->get("login");
        if ($this->api("request")->url == $loginRoute->url) return;

        $this->api("router")->redirect( $loginRoute, false );

    }


    /**
     * send the user to the correct admin landing page based on logged in state
     */
    public function gotoAdminPage()
    {

        // only run on admin route exact match
        if( $this->api("request")->url != $this->api("router")->get("admin")->url ) return false;

        if ($this->api("user")->isGuest()) $this->api("router")->redirect($this->api("router")->get("login"), false);
        else $this->api("router")->redirect($this->api("router")->get("pages"), false);

    }

    public function _render()
    {

        $this->messages = $this->getMessages();

        if ($this->page instanceof AdminPage){
            $this->output = $this->page->render();
        }

        // extract app variables for easier use in admin templates
        extract($this->api());
        include_once "layout.php";


    }


    /**
     * @param $string
     *
     * Adds a String message to be read back on next page load,
     * uses Session flash variable, is remove on access
     *
     */
    public function addMessage($string)
    {

        // retrieve messages and unserialize
        $messages = $this->api("session")->get("adminMessages");
        $messages = unserialize($messages);

        // add message to array
        $messages[] = $string;

        // serialize and store
        $messages = serialize($messages);
        $this->api("session")->flash("adminMessages", $messages);
    }

    protected function getMessages()
    {
        if ($this->api("session")->has("adminMessages")) {
            return unserialize($this->api("session")->get("adminMessages"));
        }
        return null;
    }

    /**
     * Sets the admin page that will be rendered when route matched
     */
    protected function setPage(AdminPage $page)
    {
        $this->page = $page;
    }


    public function set( string $name, $value )
    {

        switch ($name) {
            case 'page':
                $this->setPage($value);
            default:
                parent::set($name, $value);
        }
        return $this;
    }

}
