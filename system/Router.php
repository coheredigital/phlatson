<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {

    private static $routes = array();

    public static function add( $uri , callable $method )
    {
        $uri = self::normalizeUri($uri);
        self::$routes[$uri] = $method;
    }



    public static function execute( )
    {

        $uri = self::normalizeUri($_GET['uri']);

        if( isset(self::$routes[$uri] ) ){
            echo "Match!";
            call_user_func($this->routes[$uri]);
        }
        else {
            $page = api::get('pages')->get($uri);
            api::register('page', $page);
        }

    }

    protected static function normalizeUri( $uri )
    {
        return  $uri ? "/" . trim( $uri, "/") : "/";
    }

}

