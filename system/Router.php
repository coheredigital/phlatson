<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {


    private static $routes = array();

    private static $routeNames = array();

    private static $methods = array();

    private static $callbacks = array();

    private static $patterns = array(
        ':any' => '([^/]+)',
        ':num' => '([0-9]+)',
        ':all' => '(.*)'
    );


    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params)
    {
        $name = null;
        $method = strtoupper($method);

        // check for a space, assume name defined
        if( strpos( trim($params[0]) , " ") !== false){

            $paramParts = explode(" ", $params[0]);

            $name = trim($paramParts[0]);
            $url = "/" . trim($paramParts[1], "/");

        }
        else{
            $url = "/" . trim($params[0], "/");
        }

        $callback = $params[1];

        // create a key for route from Method/Url Combo
        $key = "{$method}{$url}";

        if( $name ) self::$routeNames[$name] = $key; // set the name as the key for faster lookup
        self::$routes[$key] = $url;
        self::$methods[$key] = $method;
        self::$callbacks[$key] = $callback;
    }

    public static function generate($routeName, array $params = []) {

        // Check if named route exists
        if(!isset(self::$routeNames[$routeName])) {
            throw new \Exception("Route '{$routeName}' does not exist.");
        }


        $key = self::$routeNames[$routeName];
        $route = self::$routes[$key];


        if (preg_match('#^' . $route . '$#', $route, $matched)) {

            array_shift($matched); //remove $matched[0] as [1] is the first parameter.

            if (!count($matched)) return $route; // route is static, return as is

            foreach($matched as $match) {


                list($block, $pre, $type, $param, $optional) = $match;

                if ($pre) {
                    $block = substr($block, 1);
                }

                if(isset($params[$param])) {
                    $url = str_replace($block, $params[$param], $url);
                } elseif ($optional) {
                    $url = str_replace($pre . $block, '', $url);
                }
            }


        }


        return $url;
    }


    /**
     * Runs the callback for the given request
     */
    public static function dispatch($request)
    {

        $routeMatched = false;

        $uri =  "/" . trim($request->url, "/");
        $method = $request->method;

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        // check if route is defined without regex
        if (in_array($uri, self::$routes)) {

            $route_pos = array_keys(self::$routes, $uri);

            foreach ($route_pos as $route) {

                if (self::$methods[$route] == $method) {
                    // if route not an object attempt Class:method call
                    if(!is_object(self::$callbacks[$route])){

                        $routeParts = explode('/', self::$callbacks[$route] );
                        $segments = explode('->', end($routeParts) );
                        call_user_func_array($segments, $parameters);
                        $routeMatched = true;
                    } else {
                        call_user_func(self::$callbacks[$route]);
                        $routeMatched = true;
                    }
                }
            }
        } else {
            // check if defined with regex
            foreach (self::$routes as $key => $route) {

                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$key] == $method) {

                        array_shift($matched); //remove $matched[0] as [1] is the first parameter.

                        if(!is_object(self::$callbacks[$key])){

                            //grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$key]);

                            //collect the last index of the array
                            $last = end($parts);

                            //grab the controller name and method call
                            $segments = explode('@',$last);
                            call_user_func_array( $segments , $matched );
                            $routeMatched = true;

                        } else {
                            call_user_func_array(self::$callbacks[$key], $matched);
                            $routeMatched = true;
                        }

                    }
                }

            }
        }

        // throw a Exception if no route was matched, there should be a default catch all route for pages in place so we should never arrive here
        if (!$routeMatched) {
            throw new Exception("No valid route found");
        }


    }


}

