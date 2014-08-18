<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */
class Router
{

    private $hostname;

    // routes organize in multidimensional array
    // $routes['hostname']['method']['path']
    private $routes = [];
    private $namedRoutes = [];

    public $errorCallback;

    public function __construct($hostname){
        $this->hostname = $hostname;
    }

    /**
     * Defines callback if route is not found
     */
    public function error($callback)
    {
        $this->errorCallback = $callback;
    }


    public function add(Route $route)
    {

        $domain = $route->domain ? $route->domain : api("config")->hostname;
        $method = $route->method;
        $path = $route->path;

        $key = [
            "domain" => $domain,
            "method" => $method,
            "path" => $path
        ];


        $key = serialize($key);

        $this->routes[$domain][$method][$path] = $route;
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

        // get the set to iterate based on the current request
        $routeArray = $this->routes[$request->hostname][$request->method];


        foreach ($routeArray as $route) {
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
        $key = json_decode(json_encode(unserialize($key)));
        $route = $this->routes[$key->domain][$key->method][$key->path];
        return $route;
    }


}

