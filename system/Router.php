<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */
class Router
{

    // routes organize in multidimensional array
    // $routes['hostname']['method']['path']
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


        $hostname = $route->hostname ? $route->hostname : app("config")->hostname;
        $method = $route->method;
        $path = $route->path;


        $this->routes[$hostname][$method][$path] = $route;
        if ($route->name) {

            $key = [
                "hostname" => $hostname,
                "method" => $method,
                "path" => $path
            ];

            $key = serialize($key);

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
        $halt = false;

        // get the set to iterate based on the current request
        $routeArray = $this->routes[$request->hostname][$request->method];

        foreach ($routeArray as $route) {

            if ($route->match($request)) {
                $found = true;
                $route->execute();
                if ($route->halt()) $halt = $route->halt();
            }
            else if (count($route->children)) {
                foreach ($route->children as $r) {
                    if ($r->match($request)) {
                        $found = true;
                        $r->execute();
                        if ($r->halt()) $halt = $r->halt();
                    }
                }
            }

            if($halt) break;
        }


        // run the error callback if the route was not found
        if ($found === false) {
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
     * @param $name
     * @return mixed
     *
     * use magic method to allow retrieval of named routes
     *
     */
    public function __get($name)
    {
        return $this->get($name);
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
        $key = unserialize($key);
        $key = objectify($key);
        $route = $this->routes[$key->hostname][$key->method][$key->path];
        return $route;
    }


}

