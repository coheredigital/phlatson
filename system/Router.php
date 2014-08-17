<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */
class Router
{

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


    public function add(Route $route)
    {
        $key = "{$route->method}{$route->path}";
        $this->routes[$key] = $route;
        if ($route->name) {
            $this->namedRoutes[$route->name] = $key;
        }
    }

    /**
     * Runs the callback for the given request
     * Called by __destruct()
     */
    public function run($request)
    {
        $found = false;

        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                $found = true;
                $route->execute();
            }
        }


        // run the error callback if the route was not found
        if ($found == false) {
            if (!$this->errorCallback) {
                $this->errorCallback = function () {
                    header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
                    echo '404';
                };
            }
            call_user_func($this->errorCallback);
        }

    }

    /**
     * @param $name name of route to find
     * @return Route || bool
     *
     * return a named Route object if it exists
     *
     */
    public function get($name)
    {
        if (!isset($this->namedRoutes[$name])) {
            return false;
        }

        $key = $this->namedRoutes[$name];
        return $this->routes[$key];
    }


}

