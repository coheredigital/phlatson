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
    private $routes = [];
    private $namedRoutes = [];
    private $defaultRoute;


    public function __construct(Route $defaultRoute){

        $this->defaultRoute = $defaultRoute;
    }

    /**
     * Defines callback if route is not found
     */
    public function error($callback)
    {
        $this->defaultRoute = $callback;
    }


    public function add(Route $route)
    {


        $hostname = $route->hostname ? $route->hostname : registry("config")->hostname; // by default routes are children of the default hostname
        $method = $route->method;
        $path = $route->path;


        $this->routes[$hostname][$method][$path] = $route;
//        $this->routes[$hostname][$path] = $route; // removed method requirement
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
    public function run(Request $request)
    {
        $found = false;

        // get the set to iterate based on the current request
        $routes = $this->routes[$request->hostname][$request->method];

        foreach ($routes as $route) {
            if (!$route->match($request)) continue;
            $route->execute();
            $found = true;
            break;
        }


        // default route if nothing matched
        if ($found === false && $this->defaultRoute !== false) {
            if($this->defaultRoute->match($request)){
                $this->defaultRoute->execute();
                $found = true;

            }
        }


        if ($found === false) throw new Exception("Invalid request, app cannot run");
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

