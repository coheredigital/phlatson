<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {

    private static $routes = array();

    public static function add( $name , $url , $method )
    {

        $route = new Route( $name, $url, $method );


        self::$routes[] = $route;
    }

    protected static function normalizeRoute( $uri )
    {
        $uri = $uri ? "/" . trim( $uri, "/") : "/";
        return $uri;
    }

    public static function execute()
    {



        foreach( self::$routes as $route){
            if( $route->match( api("input")->url ) ){

                $route->execute();
                return;
            }
        }

        // find a page if a route wasn't matched
        $page = api('pages')->get( $input->url );
        if( $page instanceof Page ) {
            extract( api::get() ); // get access to api variables for rendered layout
            include $page->template->layout;
        }
        else{
            throw new Exception("404");
        }



    }

}

