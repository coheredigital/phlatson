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
        $uri = self::normalizeRoute($uri);
        self::$routes[$uri] = $method;
    }



    public static function execute( )
    {

        extract( api::get() ); // get access to api variables


        if( isset( self::$routes[ $input->url ] ) ){
            echo "Match!";
            call_user_func( $this->routes[$uri] );
        }
        else {
            $page = api::get('pages')->get( $input->url );
        }


        include $page->template->layout;

    }

    protected static function normalizeRoute( $uri )
    {
        $uri = $uri ? "/" . rtrim( $uri, "/") : "/";
        return $uri;
    }

}

