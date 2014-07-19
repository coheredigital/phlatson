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



    public static function execute( )
    {

        extract( api::get() ); // get access to api variables

        if( isset( self::$routes[ $input->url ] ) ){
            call_user_func( self::$routes[$input->url] );
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


    protected function isMatch(){

    }

    protected static function normalizeRoute( $uri )
    {
        $uri = $uri ? "/" . trim( $uri, "/") : "/";
        return $uri;
    }

}

