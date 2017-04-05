<?php

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{

    private $registry = array();
    private $lock     = array();

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    final public function api(string $name = null, $value = null, bool $lock = false)
    {
        if (!is_null($name) && !is_null($value)) {
            // all APIs set this way are locked
            // return $this allows chaining
            Api::set($name, $value, $lock);
            return $this;
        } else {
            return Api::get($name);
        }
    }

    public function __get($name)
    {
        return Api::get($name);
    }

    public function __set(string $name, $value)
    {
        Api::set($name, $value, true);
    }

    /**
     * @param $method
     * @param $arguments
     * @throws Exception
     *
     * Used as a simple hook method, automatically executes events before and after any mother prefixed with and underscore
     *
     */
    public function __call($method, $arguments)
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
        $this->api("events")->execute("after.$className.$method", $event);

        return $event->return;
    }


}
