<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
final class Api
{

    private static $registry = array();
    private static $lock = array();
    private static $instance = null;

    public static function __callStatic($name, $arguments)
    {
        return static::get($name);
    }

    /**
     * @param $key
     * @param $value
     * @param bool $lock
     * @throws Exception
     */
    public static function set($key, $value, $lock = true)
    {

        if (isset(static::$registry[$key]) && in_array($key, static::$lock)) {
            throw new FlatbedException("There is already an API entry for '{$key}', value is locked.");
        }
        // set $key and $value the same to avoid duplicates
        if ($lock) static::$lock[$key] = $key;
        static::$registry[$key] = $value;

    }

    /**
     * @param null $name
     * @return array
     * @throws Exception
     */
    public function __invoke($name = null, $object = null, $lock = true)
    {

        if (!is_null($name) && !is_null($object)) {
            static::set($name, $object, $lock);
        } else {
            return static::get($name);
        }

        return false;

    }


    public static function fetchAll()
    {
        // instantiate an objects passed as string value
        foreach (static::$registry as $key => $value) {
            if (is_object($value)) continue;
            static::instantiate($key);
        }
        return static::$registry;
    }

    public static function instantiate($name)
    {
        $classname = new static::$registry[$name];
        static::$registry[$name] = new static::$registry[$name];
    }

    protected static function has($name)
    {       
        return array_key_exists($name, static::$registry);
    }

    public static function get($name)
    {
        if (is_null($name)) return static::fetchAll();
            
        if (!static::has($name)) return null;

        if (!is_object(static::$registry[$name])) {
            static::instantiate($name);
        }
        


        return static::$registry[$name];
        
    }

}