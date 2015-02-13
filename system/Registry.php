<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
class Registry
{

    private static $items = array();
    private static $lock = array();


    public static function __callStatic($name, $arguments)
    {
        if (is_null($name)) {
            return self::$items;
        }

        if (isset(self::$items[$name])) {
            return self::$items[$name];
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

        if (isset(static::$items[$key]) && in_array($key, static::$lock)) {
            throw new Exception("There is already an API entry for '{$key}', value is locked.");
        }
        // set $key and $value the same to avoid duplicates
        if ($lock) {
            static::$lock[$key] = $key;
        }

        static::$items[$key] = $value;

    }

    public function get($name){

        if (isset(static::$items[$name])) {
            return static::$items[$name];
        }

    }
    public function __get($name){
        $this->get($name);
    }


    /**
     * @param null $key
     * @return array
     * @throws Exception
     */
    public static function __invoke($key = null, $value = null, $lock = false)
    {

        if (!is_null($key) && !is_null($value)) {

            if (isset(static::$items[$key]) && in_array($key, static::$lock)) {
                throw new Exception("There is already an API entry for '{$key}', value is locked.");
            }
            // set $key and $value the same to avoid duplicates
            if ($lock) {
                static::$lock[$key] = $key;
            }

            static::$items[$key] = $value;

        } else {
            if (!is_null($key)) {
                return $this->get($key);
            }


        }





        return static::$items;

    }


}
