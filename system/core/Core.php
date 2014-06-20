<?php

/*

Core class to connect most other system classes, stores system wide api variables

 */

abstract class Core
{

    // private static $apis = array();
    protected static $registry = null;
    private $className = null;

    /*Init function sets up default api objects*/
    public static function init(Config $config)
    {
        self::api('config', $config, true);
        self::api('sanitizer', new Sanitizer(), true);
        self::api('input', new Input(), true);
        self::api('users', new Users(), true);
        self::api('session', new Session(), true);
        self::api('extensions', new Extensions(), true);
        self::api('fields', new Fields(), true);
        self::api('templates', new Templates(), true);
        self::api('pages', new Pages(), true);
    }

    // method to get reference to chache api class
    // if $value provide, use as a "setter"
    public static function api($name = null, $object = null, $lock = false)
    {
        // instantiate the registry if it doesn't yet exist
        if (!isset(self::$registry)) {
            self::$registry = new Registry();
        }

        // return registry if no $name supplied
        if (is_null($name)) {
            return self::$registry;
        }

        // uses as getter if no object passed
        if (is_null($object)) {
            return self::$registry->get($name);
        } else {
            if (!is_null($object)) {
                self::setApi($name, $object, $lock);
            }
        }
    }

    public static function setApi($name, $value, $lock = false)
    {

        if (!isset(self::$registry)) {
            self::$registry = new Registry();
        }
        self::$registry->set($name, $value, $lock);
    }


    public function className()
    {

        if (!isset($this->className)) {
            $this->className = get_class($this);
        }
        return $this->className;

    }

    public function __toString()
    {
        return $this->className();
    }

}