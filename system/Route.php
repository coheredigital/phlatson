<?php


class Route extends App
{

    use hookable;

    private $allowedMethods = [
        "GET",
        "POST",
        "PUT",
        "DELETE"
    ];


    private $name = null;
    private $path = null;
    private $scheme = "http";
    private $hostname = false;

    public $ssl = false;

    private $parent;
    private $children;

    private $method = "GET";

    private $callbacks = [
        "before" => [],
        "default" => [],
        "after" => []
    ];


    private $filters = [];
    private $parameters = [];

    private $patterns = array(
        ':any' => '([^/]+)',
        ':all' => '(.*)'
    );

    private $response = null;

    public function __construct($options = [])
    {

        /* init children collection*/
        $this->children = new RouteCollection();

        if (isset($options["method"])) {
            $this->method($options["method"]);
        }

        if (isset($options["hostname"])) {
            $this->hostname($options["hostname"]);
        }

        if (isset($options["path"])) {
            $this->path($options["path"]);
        }

        if (isset($options["before"])) {
            $this->before($options["before"]);
        }
        if (isset($options["callback"])) {
            $this->callback($options["callback"]);
        }
        if (isset($options["after"])) {
            $this->after($options["after"]);
        }

        if (isset($options["parent"])) {
            $this->parent($options["parent"]);
        }

        if (isset($options["name"])) {
            $this->name($options["name"]);
        }

    }


    public function hostname($domain = null)
    {

        if ($domain) {
            $this->hostname = $domain;
            return $this;
        } else {

            if ($this->hostname) {
                return $this->hostname;
            } else {
                if ($this->parent) {
                    return $this->parent->hostname();
                } else {
                    return $this->api("config")->hostname;
                }
            }

        }


    }


    /**
     * @param $path
     * @return $this
     *
     * Used as a url getter/setter
     *
     */
    public function path($path = null)
    {

        if (is_null($path)) {

            $path = $this->path;
            if ($this->parent instanceof Route) {
                $path = trim($this->parent->path, "/") . "/" . trim($path, "/");
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

    /**
     * @param array $parameters
     * @return null|string
     *
     * return a url for the route
     */
    private function url(array $parameters = [])
    {

        if (count($parameters)) {
            // generate url, replace regex with parameters
        } else { // return url without using parameters
            $url = $this->path;
            if ($this->parent instanceof Route) {
                $url = trim($this->parent->url, "/") . "/" . trim($url, "/") . "/";
            } else {
                $path = trim($url, "/") ? "/" . trim($url, "/") . "/" : "/";
                $url = $this->scheme . "://" . trim($this->hostname(), "/") . $path;
            }
            return $url;
        }

    }

    public function parent($route)
    {
        if (is_string($route)) {
            $route = $this->api("router")->get($route);
        }

        if (!$route instanceof Route) {
            throw new FlatbedException("Invalid route: cannot be added as parent");
        }

        $this->parent = $route;
        $route->addChild($this);
        return $this;
    }

    public function addChild(Route $route)
    {
        $route->parent = $this;
        $this->children->add($route);
    }

    public function method($name = null)
    {
        if($name){
            $method = trim(strtoupper($name));
            $methods = explode("|", $method);
            if (array_diff($methods, $this->allowedMethods)) {
                throw new FlatbedException("Invalid method '$method' must use one of the predetermined methods for all routes ( " . implode(", ", $this->allowedMethods) . " ).");
            }

            $this->method = $method;
            return $this;
        }
        else{

        }

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


    public function filter($callback)
    {
        if (is_callable($callback)) {
            array_push($this->filters, $callback);
            return $this;
        } else {
            // run filters
        }

    }


    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     *
     */
    public function callback($callback, $set = "default")
    {
        array_push($this->callbacks[$set], $callback);
        return $this;
    }

    public function before($callback)
    {
        $this->callback($callback, "before");
        return $this;
    }

    public function after($callback)
    {
        $this->callback($callback, "after");
        return $this;
    }


    /**
     * @param $request
     * @return bool
     *
     * Return true if the request is a match for this Route
     *
     */
    public function match($request)
    {

        $url = $this->url();
        $routeMethods = explode("|",$this->method);


        if(!in_array($request->method, $routeMethods)){
            return false;
        }


        // check exact match to url & method
        if ($url == $request->url) {
            return true;
        }

        // check for pattern match potential
        if (strpos($url, ':') !== false) {
            $searches = array_keys($this->patterns);
            $replaces = array_values($this->patterns);
            $url = str_replace($searches, $replaces, $url);
        }

        if (preg_match("#^" . $url . "$#", $request->url, $matched)) {
            array_shift($matched); //remove $matched[0] as [1] is the first parameter.
            $this->parameters = $matched;
            return true;
        }


        return false;
    }

    public function execute()
    {

        if ($this->parent instanceof Route) {
            $this->parent->executeCallbacks("before");
        }

        $this->executeCallbacks("before");

        // first execute parent routes in order
        if ($this->parent instanceof Route) {
            $this->parent->executeCallbacks();
        }

        $this->executeCallbacks();
        $this->executeCallbacks("after");
        if ($this->parent instanceof Route) {
            $this->parent->executeCallbacks("after");
        }
    }

    public function executeCallbacks($set = "default")
    {

        if (!count($this->callbacks[$set])) return false;

        foreach ($this->callbacks[$set] as $callback) {

            if (!is_object($callback)) {

                //grab all parts based on a / separator
                $parts = explode('/', $callback);

                //collect the last index of the array
                $last = end($parts);

                //grab the class name and method
                if (strpos($last, ".") !== false) { // check to see if a specific method was defines
                    $segments = explode(".", $last);
                    $className = $segments[0];
                    $methodName = $segments[1];
                } else { // else run a method that matches the METHOD type defined in this $route
                    $className = $last;
                    $methodName = strtolower($this->method);
                }

                $class = new $className;

                //call method and pass any extra parameters to the method
                if (!is_callable([$class, $methodName])) {
                    throw new FlatbedException("Method: $methodName does not exist in class: $className");
                }
                call_user_func_array([$class, $methodName], $this->parameters);

            } else {
                call_user_func_array($callback, $this->parameters);
            }

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
            case "hostname":
            case "url":
                return $this->{$name}();
            case "name":
            case "method":
            case "children":
            case "parent":
                return $this->{$name};
            default:
                return false;
        }
    }


}

