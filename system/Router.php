<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {

    private $request;
    
    private $routes = [];
    private $namedRoutes = [];

    public $errorCallback;

    /**
     * Defines callback if route is not found
     */
    public function error($callback)
    {
        $this->errorCallback = $callback;
    }


    public function add( Route $route ){
        $this->routes["{$route->method}{$route->url}"] = $route;
        if( $route->name ){
            $this->namedRoutes[$route->name] = $this->url;
        }
    }

    /**
     * Runs the callback for the given request
     * Called by __destruct()
     */
    public function run()
    {

        $request = api('request');

        $url =  "/" . trim( $request->url, "/");
        $method = $request->method;
        $routeKey = $method . $url;

        $found = false;

        // check if route is defined without regex
        if ( isset($this->routes[$routeKey] ) ) {
            $found = true;
            $this->routes[$routeKey]->execute();
        }
        else {
            foreach ( $this->routes as $route) {
                if ( $route->definitionCount === 0 ) continue; // skip if $route not REGEX type
                if ( $route->match($request) ) {
                    $found = true;
                    $route->execute();
                }

            }
        }


        // run the error callback if the route was not found
        if ($found == false) {
            if (!$this->errorCallback) {
                $this->errorCallback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '404';
                };
            }
            call_user_func($this->errorCallback);
        }

    }

    /**
     * Autorun the Router if $this->autorun is set to true
     */
//    function __destruct() {
//        $this->run();
//    }

}

