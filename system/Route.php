<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Route{

    private $methods = [
        "GET",
        "POST",
        "PUT",
        "DELETE"
    ];

    private $name;
    private $url;

    private $method = "GET";
    private $callbacks = [];
    private $parameters = [];

    private $patterns = array(
        ':any' => '([^/]+)',
        ':num' => '([0-9]+)',
        ':all' => '(.*)'
    );

    public function __construct($url = null, $callback = null){
        if ($url){
            $this->url($url);
        }
        if ( $callback ) {
            $this->callback($callback);
        }
    }

    /**
     * @param $url
     * @return $this
     *
     * Used as a url getter/setter
     * if
     *
     */
    public function url( $url ){

        if ( strpos( trim($url), " ") !== false){ // assume that a URL with a space defined an alternative method
            $urlParts = explode(" ",$url);
            $this->method( $urlParts[0] );
            $this->url = trim($urlParts[1]);
        }
        else{
            $this->url = $url;
        }

        return $this;
    }

    public function method($name){


        $method = trim( strtoupper($name) );
        if (!in_array( $method,$this->methods )) {
            throw new Exception("Invalid method '$method' must use one of the predetermined methods for all routes ( " . implode(", " , $this->methods) . " )." );
        }

        $this->method = $method;

    }

    /**
     * @param $name
     * @return $this
     *
     * Give the route a name so it can be retrieved later to be altered or added to
     * Cannot use generate method unless a Route has been given a name
     * Names must be set before being added to the Router object
     */
    public function name($name){
        $this->name = $name;
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     *
     * appends new callback to the end of the callbacks set
     *
     */
    public function callback($callback){
        array_push($this->callbacks , $callback);
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
    public function appendCallback($callback){
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
    public function prependCallback($callback){
        array_unshift($this->callbacks , $callback);
        return $this;
    }

    public function match($request){

        // check method
        if ( $this->method && $this->method !== $request->method ) return false;

        $searches = array_keys( $this->patterns );
        $replaces = array_values( $this->patterns );

        if (strpos( $this->url  , ':') !== false) {
            $this->url = str_replace($searches, $replaces, $this->url );
        }

        if (preg_match('#^' . $this->url . '$#', $request->url , $matched)) {

            array_shift($matched); //remove $matched[0] as [1] is the first parameter.

            $this->parameters = $matched;
            return true;
        }


        return false;
    }

    public function execute(){
        foreach ( $this->callbacks as $callback ) {

            if(!is_object($callback)){

                //grab all parts based on a / separator
                $parts = explode('/', $callback);

                //collect the last index of the array
                $last = end($parts);

                //grab the class name and method
                if (strpos( $last, "@" ) !== false) { // check to see if a specifc method was defines
                    $segments = explode( "@", $last );
                    $className = $segments[0];
                    $methodName = $segments[1];
                }
                else{ // else run a method that matches the METHOD type defined in this $route
                    $className = $last;
                    $methodName = strtolower($this->method);
                }

                //call method and pass any extra parameters to the method
                if ( !is_callable([$className , $methodName])){
                    throw new Exception("Method: $methodName does not exist in class: $className");
                }
                call_user_func_array( [$className , $methodName] , $this->parameters );

            } else {
                call_user_func_array($callback, $this->parameters );
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
    public function __get($name){
        switch ($name) {
            case "url":
            case "name":
            case "method":
                return $this->{$name};
        }
    }

}

