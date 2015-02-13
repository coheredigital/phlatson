<?php

trait hookable {
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

        if (!is_callable([$this, "_$method"])) {
            throw new Exception("Method: $method does not exist in class: $this->className");
        }

        $className = get_class($this);

        // create the Event object to store and pass all the good stuff we want to have available to our listeners
        $event = new Event;

        $event->method = $method;
        $event->arguments = $arguments;
        $event->object = $this;
        $event->return = null;


        // TODO :  work on function return handling logic
        $event->return = registry("events")->execute("before.$className.$method", $event);
        // call the real method and pass the arguments from the Event reference (this allows for interception and alterations)
        $event->return = call_user_func_array([$this, "_$method"], $event->arguments);
        registry("events")->execute("after.$className.$method", $event);

        return $event->return;
    }
}