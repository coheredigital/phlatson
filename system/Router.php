<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Router {

    private static $halts = false;

    private static $routes = array();

    private static $methods = array();

    private static $callbacks = array();

    private static $patterns = array(
        ':any' => '([^/]+)',
        ':num' => '([0-9]+)',
        ':all' => '(.*)'
    );

    public static $error_callback;

    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params)
    {

        $uri = "/" . trim($params[0], "/");
        $callback = $params[1];

        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }


    /**
     * Defines callback if route is not found
     */
    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    /**
     * Runs the callback for the given request
     */
    public static function dispatch($request)
    {
        $uri =  "/" . trim($request->url, "/");
        $method = $request->method;

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        // check if route is defined without regex
        if (in_array($uri, self::$routes)) {

            $route_pos = array_keys(self::$routes, $uri);

            foreach ($route_pos as $route) {

                if (self::$methods[$route] == $method) {
                    //if route is not an object
                    if(!is_object(self::$callbacks[$route])){

                        //grab all parts based on a / separator
                        $parts = explode('/', self::$callbacks[$route] );

                        //collect the last index of the array
                        $last = end($parts);

                        //grab the controller name and method call
                        $segments = explode('->',$last);

                        call_user_func_array($segments, $parameters);

                        return; // end when match

                    } else {
                        //call closure
                        call_user_func(self::$callbacks[$route]);
                        return; // end when match
                    }
                }
            }
        } else {
            // check if defined with regex
            $pos = 0;
            foreach (self::$routes as $route) {

                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method) {

                        array_shift($matched); //remove $matched[0] as [1] is the first parameter.

                        if(!is_object(self::$callbacks[$pos])){

                            //grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$pos]);

                            //collect the last index of the array
                            $last = end($parts);

                            //grab the controller name and method call
                            $segments = explode('@',$last);

                            call_user_func_array( $segments , $matched );

                            return;
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);

                            return;
                        }

                    }
                }
                $pos++;
            }
        }

        // run the error callback if the route was not found
        if (!self::$error_callback) {
            self::$error_callback = function() {
                header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                echo '404';
            };
        }
        call_user_func(self::$error_callback);


    }


}

