<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */
class Route
{

    private $methods = [
        "GET",
        "POST",
        "PUT",
        "DELETE"
    ];

    private $name;
    private $scheme = "http";
    private $path;
    private $domain = false;

    public $ssl = false;

    private $parent = false;
    private $children = [];

    private $method = "GET";
    private $callbacks = [];
    private $parameters = [];

    public $halt = false;

    private $patterns = array(
        ':any' => '([^/]+)',
        ':num' => '([0-9]+)',
        ':all' => '(.*)'
    );

    public function __construct($parameters = [])
    {
        if ($parameters["method"]) {
            $this->method($parameters["method"]);
        }
        if ($parameters["domain"]) {
            $this->domain($parameters["domain"]);
        }
        if ($parameters["path"]) {
            $this->path($parameters["path"]);
        }
        if ($parameters["callback"]) {
            $this->callback($parameters["callback"]);
        }
        if ($parameters["parent"]) {
            $this->parent($parameters["parent"]);
        }
        if ($parameters["name"]) {
            $this->name($parameters["name"]);
        }

    }


    public function domain($domain = null)
    {

        if($domain){
            $this->domain = $domain;
            return $this;
        }
        else{

            if ($this->domain) return $this->domain;
            else if($this->parent){
                return $this->parent->domain();
            }
            else{
                return api("config")->hostname;
            }

        }


    }

    public function halt($halt = true)
    {
        $this->halt = $bool;
        return $this;
    }

    /**
     * @param $path
     * @return $this
     *
     * Used as a url getter/setter
     * if
     *
     */
    public function path($path = null)
    {

        if (is_null($path)) {

            $path = $this->path;
            if ($this->parent instanceof Route) {
                $pUrl = $this->parent->path();
                $path = $pUrl . $path;
            }
            return $path;

        } else {

            if (strpos(trim($path), " ") !== false) { // assume that a URL with a space defined an alternative method
                $pathParts = explode(" ", $path);
                $this->method($pathParts[0]);
                $this->path = '/' . trim($pathParts[1], '/');
            } else {
                $this->path = '/' . trim($path, '/');
            }
            return $this;

        }

    }

    public function parent(Route $route)
    {
        $this->parent = $route;
        $route->children[] = $this;
        return $this;
    }

    public function addChild(Route $route)
    {
        $this->children[] = $route;
        $route->parent = $this;
        return $this;
    }


    public function method($name)
    {

        $method = trim(strtoupper($name));
        if (!in_array($method, $this->methods)) {
            throw new Exception("Invalid method '$method' must use one of the predetermined methods for all routes ( " . implode(
                    ", ",
                    $this->methods
                ) . " ).");
        }

        $this->method = $method;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     *
     * Give the route a name so it can be retrieved later to be altered or added to
     * Cannot use generate method unless a Route has been given a name
     * Names must be set before being added to the Router object
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    private function url()
    {

        $url = $this->path;
        if ($this->parent instanceof Route) {
            $pUrl = $this->parent->url();
            $url = $pUrl . $url;
        }
        else {
            $url = $this->scheme . "://" . $this->domain() . ltrim($url, "/");
        }



        return $url;
    }


    public function generate($parameters=[])
    {
        $url = $this->url();
        return $url;
    }

    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     *
     */
    public function callback($callback)
    {
        array_push($this->callbacks, $callback);
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     * alias of callback() method for logical consistency
     *
     */
    public function appendCallback($callback)
    {
        $this->callback($callback);
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     * alias of callback() method for logical consistency
     *
     */
    public function prependCallback($callback)
    {
        array_unshift($this->callbacks, $callback);
        return $this;
    }

    public function match($request)
    {

        $path = $this->path();
        $url = $this->url();


        // check exact match to url
        if ($url == $request->url) {
            return true;
        }

        // check method
        if ($this->method !== $request->method) {
            return false;
        }

        $searches = array_keys($this->patterns);
        $replaces = array_values($this->patterns);


        if (strpos($path, ':') !== false) {
            $path = str_replace($searches, $replaces, $path);
        }

        if (preg_match("#^" . $path . "$#", $request->path, $matched)) {
            array_shift($matched); //remove $matched[0] as [1] is the first parameter.
            $this->parameters = $matched;
            return true;
        }


        return false;
    }

    public function execute()
    {

        if ($this->parent) {
            $this->parent->execute();
        }

        foreach ($this->callbacks as $callback) {

            if (!is_object($callback)) {

                //grab all parts based on a / separator
                $parts = explode('/', $callback);

                //collect the last index of the array
                $last = end($parts);

                //grab the class name and method
                if (strpos($last, ":") !== false) { // check to see if a specific method was defines
                    $segments = explode(":", $last);
                    $className = $segments[0];
                    $methodName = $segments[1];
                } else { // else run a method that matches the METHOD type defined in this $route
                    $className = $last;
                    $methodName = strtolower($this->method);
                }

                $class = new $className;

                //call method and pass any extra parameters to the method
                if (!is_callable([$class, $methodName])) {
                    throw new Exception("Method: $methodName does not exist in class: $className");
                }
                call_user_func_array([$class, $methodName], $this->parameters);

            } else {
                call_user_func_array($callback, $this->parameters);
            }

            if ( $this->halt) break;

        }
    }


    /**
     * @param $name
     * @return mixed
     *
     * use magic method to allow retrieval of select private properties
     *
     */
    public function __get($name)
    {
        switch ($name) {
            case "path":
                return $this->path();
            case "name":
            case "method":
                return $this->{$name};
            case "domain":
                return $this->domain();
            case "url":
                return $this->url();

        }
    }


}

