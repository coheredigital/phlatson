<?php
namespace Flatbed;
namespace Flatbed;

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{


    public $callbacks = [];
    public $callbacksBefore = [];
    public $callbacksAfter = [];


    /**
     * get or register API variable 
     * universally accesible to classes extending Flatbed
     * @param  string       $name  unique name that the API is registered under
     * @param  mixed        $value the value to be stored
     * @param  bool|boolean $lock  set to true to prevent the API variable being overwritten
     * @return self         returns self to allow chaining
     */
    final public function api( ?string $name = null, $value = null, bool $lock = false)
    {

        if ($value !== null) {
            Api::set($name, $value, $lock);
            return $this;
        }

        return Api::get($name);
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

        // first check for simple bound methods
        if ( isset($this->callbacks[$method] ) ) {
            
            return call_user_func($this->callbacks[$method], $arguments);
        }


        // then check if a exentable method exists
        $methodName = "_$method";
        $className = get_class($this);



        // TODO :  work on function return handling logic
        // $event->return = $this->api("events")->execute("before.$className.$method", $event);
        // call the real method and pass the arguments from the Event reference (this allows for interception and alterations)
        // $event->return = call_user_func_array([$this, "_$method"], $event->arguments);
        // $this->api("events")->execute("after.$className.$method", $event);

        // return $event->return;
    }

    

    /**
     * allow for more convenient access to the API
     * @param  $string $name name one the api variable we want access to
     * @return Object    API object
     */
    final public function __invoke($name)
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
    public function __get( string $name)
    {
        switch ($name) {
            case 'url':
                return $this->{$name};
            default:
                return null;
        }
    }


    /**
     * bind a new method to the class
     *
     * @param string $name
     * @param callback $callback
     * @return void
     */
    final public function bind( string $name, Callable $function) {

        $classname = (new \ReflectionClass($this))->isAnonymous() ? get_parent_class($this) : get_class($this);

        if (method_exists($this, "$name")) {
            throw new Exceptions\FlatbedException("Cannont bind method '$name' to {$classname} : method already exists!");
        }

        $this->callbacks[$name] = $function;
    }
    final public function bindBefore( string $name, Callable $function) {
        $this->callbacks[$name] = $function;
    }

    final public function bindAfter( string $name, Callable $function) {
        $this->callbacks[$name] = $function;
    }


}
