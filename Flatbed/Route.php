<?php

namespace Flatbed;

class Route
{

    protected $allowedMethods = [
        "GET",
        "POST",
        "PUT",
        "DELETE",
        "UPDATE"
    ];


    protected $name = null;
    protected $method = "GET";
    protected $path = null;
    protected $scheme = "http";
    protected $hostname = false;

    public $ssl = false;

    protected $parent;
    protected $children;

    protected $priority;

    public $callback;

    // public $callbacks = [
    //     "before" => [],
    //     "default" => [],
    //     "after" => []
    // ];


    protected $parameters = [];

    protected $patterns = array(
        ':any' => '([^/]+)',
        ':all' => '(.*)'
    );


    public function __construct($options = [])
    {


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
            $this->callback = $options["callback"];
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
                $path = $this->parent->path . $path;
            }
            return $path;

        } else {

            if (strpos(trim($path), " ") !== false) { // assume that a URL with a space defined an alternative method
                $pathParts = explode(" ", $path);
                $this->method($pathParts[0]);
                $this->path = '/' . trim($pathParts[1], '/');
            } else {
                $this->path = '/' . trim($path, '/') . "/";
            }
            return $this;

        }

    }


    public function parent($route)
    {
        if (is_string($route)) {
            $route = $this->api("router")->get($route);
        }

        if (!$route instanceof Route) {
            throw new Exceptions\FlatbedException("Invalid route: cannot be added as parent");
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
                throw new Exceptions\FlatbedException("Invalid method '$method' must use one of the predetermined methods for all routes ( " . implode(", ", $this->allowedMethods) . " ).");
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



    /**
     * Determines priority value;
     */

    public function priority(){

        if($this->priority) return $this->priority;

        $path = $this->path();

        if(strpos($path, ':') !== false){
            $priority = (substr_count($path, "/") + substr_count($path, ":")) * 2; // longer more specific urls first
            $parts = explode(":", $path);

            // boost ie this "/pages:all" over "/:all" or similar, first example must match first
            foreach($parts as $key => $part){
                if(substr($part, -1) != "/") $priority++;
            }
        }
        else{
            $priority = substr_count($path, "/") * 100; // longer more spcific urls first
        }


        $this->priority = $priority;
        return $priority;
    }

    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     *
     */
    public function callback( Callable $callback, $set = "default")
    {
        // array_push($this->callbacks[$set], $callback);
        $this->callback = $callback;
        return $this;
    }

    // public function before( Callable $callback)
    // {
    //     $this->callback($callback, "before");
    //     return $this;
    // }

    // public function after( Callable $callback)
    // {
    //     $this->callback($callback, "after");
    //     return $this;
    // }


    /**
     * @param $request
     * @return bool
     *
     * Return true if the request is a match for this Route
     *
     */
    public function match( Request $request) : bool
    {

        // store route path as variable
        $path = $this->path();
        
        // check that the request method matches the allowed methods for this route
        if(!in_array($request->method, $this->methods)){
            return false;
        }
        
        // check exact match to url & method
        if ($path == $request->path) {
            return true;
        }

        // check for pattern match potential (if none then return false as the exact match didn't occur above)
        if (strpos($path, ':') === false) return false;


        $path = str_replace(
            array_keys($this->patterns),
            array_values($this->patterns),
            $path
        );
        $path = "/" . trim($path, "/");

        if (preg_match("#^" . $path . "$#", $requestPath, $matched)) {
            array_shift($matched); //remove $matched[0] as [1] is the first parameter.
            $this->parameters = $matched;
            return true;
        }

        return false;
    }

    public function execute($response) : Response
    {
        call_user_func(
            $this->callback,
            $response
        );
        return $response;
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
                    throw new Exceptions\FlatbedException("Method: $methodName does not exist in class: $className");
                }
                call_user_func_array([$class, $methodName], $this->parameters);

            } else {

                $args = func_get_args();
                d($args);
                return call_user_func_array(
                    $this->callback,
                    $args
                );

                call_user_func_array($callback, $args);
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
            case "methods":
                return explode("|",$this->method);
            default:
                return false;
        }
    }


}
