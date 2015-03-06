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

        app("admin", $this); // register api variable

        $this->children = new ObjectCollection();

        // default admin scripts and styles
        app("config")->styles->add("{$this->url}styles/admin.css");
        app("config")->scripts->add("{$this->url}scripts/jquery-sortable.js");
        app("config")->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
        app("config")->scripts->add("{$this->url}scripts/main.js");
        app("config")->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");


        if (app("router")->get("admin")) {
            $this->route = app("router")->get("admin");
        } else throw new FlatbedException("Admin route missing from Site.json configuration file.");

        // add the admin URL to the config urls variable for easy access/reference
        app("config")->urls->admin = $this->route->url;


        $logoutRoute = new Route;
        $logoutRoute->path("logout")
            ->name("logout")
            ->parent($this->route)
            ->callback(
                function () {
                    app("session")->logout();
                    app("router")->redirect($this->route);
                }
            );
        app('router')->add($logoutRoute);


        $login = new Route;
        $login
            ->name("login")
            ->path("login")
            ->parent("admin")
            ->before(function(){
                if(!app("user")->isGuest()){
                    $pages = app("router")->get("pages");
                    app("router")->redirect($pages);
                }
            })
            ->callback(function(){
                //  add the login stylesheet and load the login layout
                app("config")->styles->add("{$this->url}styles/login.css");
                include "login.php";

            });
        app('router')->add($login);


        $loginSubmit = new Route;
        $loginSubmit
            ->path("login")
            ->method("POST")
            ->parent("admin")
            ->callback(
                function () {
                    if (app("session")->login(app("request")->post->username, app("request")->post->password)) {
                        app("router")->redirect(app("router")->get("admin")->url);
                    }


                }
            );
        app('router')->add($loginSubmit);

    }


    public function authorize()
    {

        $app = app();
        if ($app["user"]->isGuest()) {

            if (app("request")->url != app("router")->login->url) {
                app("router")->redirect($app["router"]->login->url);
            }

        }

    }

    public function _render()
    {

        extract(app());
        $this->messages = $this->getMessages();

//        if ($user->isGuest()) {
//
//
//            // add the login stylesheet and load the login layout
//            app("config")->styles->add("{$this->url}styles/login.css");
//            include "login.php";
//        }
//        else if ($user->isLoggedIn()) {
//            if ($request->url == $router->login->url || $request->url == $router->admin->url) $router->redirect($router->admin->url . "pages");

            if ($this->page instanceof AdminPage)
                $this->output = $this->page->render();

            if ($this->output) include_once "layout.php";
//        }

    }


    public function getSettings()
    {


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
        $messages = app("session")->get("adminMessages");
        $messages = unserialize($messages);

        // add message to array
        $messages[] = $string;

        // serialize and store
        $messages = serialize($messages);
        app("session")->flash("adminMessages", $messages);
    }

    protected function getMessages()
    {
        if (app("session")->has("adminMessages")) {
            return unserialize(app("session")->get("adminMessages"));
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


    public function set($name, $value)
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