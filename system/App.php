<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
final class App
{

    private static $registry = array();
    private static $lock = array();


    public static function __callStatic($name, $arguments)
    {
        if (is_null($name)) static::fetchAll();

        if (isset(self::$registry[$name])) {
            return self::get($name);
        }

        return false;
    }

    /**
     * @param $key
     * @param $value
     * @param bool $lock
     * @throws Exception
     */
    public static function set($key, $value, $lock = false)
    {

        if (isset(self::$registry[$key]) && in_array($key, self::$lock)) {
            throw new Exception("There is already an API entry for '{$key}', value is locked.");
        }
        // set $key and $value the same to avoid duplicates
        if ($lock) {
            self::$lock[$key] = $key;
        }

        self::$registry[$key] = $value;

    }

    /**
     * @param null $key
     * @return array
     * @throws Exception
     */
    public static function __invoke($key = null, $value = null, $lock = false)
    {

        if (!is_null($key) && !is_null($value)) {

            if (isset(self::$registry[$key]) && in_array($key, self::$lock)) {
                throw new Exception("There is already an API entry for '{$key}', value is locked.");
            }
            // set $key and $value the same to avoid duplicates
            if ($lock) {
                self::$lock[$key] = $key;
            }

            self::$registry[$key] = $value;

        } else {
            if (is_null($key)) {
                return static::fetchAll();
            }

            if (isset(self::$registry[$key])) {
                return self::get($key);
            }
        }


        return false;

    }


    public static function fetchAll(){
        // instantiate an objects passed as string value
        foreach(self::$registry as $key => $value){
            if(is_object($value)) continue;
            self::instantiate($key);
        }
        return self::$registry;
    }

    public static function instantiate($name)
    {
        self::$registry[$name] = new self::$registry[$name];
    }

    public static function get($name)
    {
        if(!isset(self::$registry[$name])){
            return false;
        }
        if(!is_object(self::$registry[$name])) {
            self::instantiate($name);
        }
        return self::$registry[$name];
    }

}
