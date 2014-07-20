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

        foreach( self::$routes as $route){
            $match = $route->match($input->url);
            if( $match ) {

               break;
            }
        }

        if( $route ){
            $route->execute();
        }
        else {
            $page = api('pages')->get( $input->url );

            if( $page instanceof Page ) {
                include $page->template->layout;
            }
//            else{
//                throw new Exception("404");
//            }

        }

    }

}

