<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {



    private static $routes = array();


    public static function add( $uri , $method )
    {
        $uri = self::normalizeRoute($uri);
        self::$routes[$uri] = $method;
    }




    protected static function getMatch($uri){

        foreach ( self::$routes as $key => $value){
            if( strpos($uri, $key) === 0){
                return $value;
            }
        }
    }

    protected static function normalizeRoute( $uri )
    {
        $uri = $uri ? "/" . trim( $uri, "/") : "/";
        return $uri;
    }




    public static function execute( )
    {

        extract( api::get() ); // get access to api variables

        $match = self::getMatch( $input->url );

        if( $match ){
            call_user_func( $match );
        }
        else {
            $page = api::get('pages')->get( $input->url );

            if( $page instanceof Page ) {
                include $page->template->layout;
            }
            else{
                throw new Exception("404");
            }

        }

    }

}

