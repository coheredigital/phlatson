<?php

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{

    /**
     * @param $name
     */
    final public function api(string $name = null)
    {
        return Api::get($name);
    }

    /**
     * register new API variable to be universally accesible to classes extending Flatbed
     * @param  string       $name  unique name that the API is registered under
     * @param  mixed        $value the value to be stored
     * @param  bool|boolean $lock  set to true to prevent the API variable being overwritten
     * @return self         returns self to allow chaining
     */
    public function register(string $name, $value, bool $lock = false) : self
    {
        Api::set($name, $value, $lock);
        return $this;
    }


    /**
     * @param $method
     * @param $arguments
     * @throws Exception
     *
     * Used as a simple hook method, automatically executes events before and after any mother prefixed with and underscore
     * no other classes that extend Flatbed should be allowed to override this behaviour
     *
     */
    final public function __call($method, $arguments)
    {
        $methodName = "_$method";
        if (!method_exists($this, "$methodName")) throw new FlatbedException("Method: $method does not exist in class: $this->className");

        $className = get_class($this);

        // create the Event object to store and pass all the good stuff we want to have available to our listeners
        $event = new Event;

        $event->method = $method;
        $event->arguments = $arguments;
        $event->object = $this;
        $event->return = null;


        // TODO :  work on function return handling logic
        $event->return = $this->api("events")->execute("before.$className.$method", $event);
        // call the real method and pass the arguments from the Event reference (this allows for interception and alterations)
        $event->return = call_user_func_array([$this, "_$method"], $event->arguments);
        // $this->api("events")->execute("after.$className.$method", $event);

        return $event->return;
    }

    /**
     * allow for more convenient access to the API
     * @param  $string $name name one the api variable we want access to
     * @return Object    API object
     */
    final public function __invoke($name, $value = null, $lock = false)
    {
        // $this->api($name, $value, $lock);
        return Api::get($name);
    }

    public function get(string $name)
    {
        switch ($name) {
            case 'className':
                return get_class($this);
            default:
                return null;
        }
    }

    /**
     * give property access to all get() variables
     * @param  string $name
     * @return mixed
     */
    final public function __get( string $name)
    {
        return $this->get($name);
    }

}
