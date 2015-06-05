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


        $hostname = $route->hostname ? $route->hostname : app("config")->hostname; // by default routes are children of the default hostname
        $method = $route->method;
        $path = $route->path;

        $key = $method . $path;


        $this->routes[$hostname][$key] = $route;

        if ($route->name) {
            $this->namedRoutes[$route->name] = $route;
        }



    }

    /**
     * @param Request $request
     * @throws FlatbedException
     *
     */
    public function run(Request $request)
    {
        $found = false;

        // get the set to iterate based on the current request
        $routes = $this->routes[$request->hostname];

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


        if ($found === false) throw new FlatbedException("Invalid request, app cannot run");
    }


    /**
     * redirects page using PHP header
     * @param  string or Page object  $url   redirect to url from root or to page
     * @param  boolean $permanent is redirect permanent?
     */
    public function redirect($value, $permanent = true)
    {


        switch($value){
            case ($value instanceof Page):
            case ($value instanceof Route):
                $url = $value->url;
                break;
            default:
                $url = $value;
                break;
        }



        // perform the redirect
        if ($permanent) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: $url");
        header("Connection: close");
        exit(0);
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

        $route = $this->namedRoutes[$name];
        return $route;
    }


}

