<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
final class api implements IteratorAggregate
{

    private static $registry = array();
    private static $lock = array();


    /**
     * set registry API by key
     * @param string $key
     * @param object $value
     */
    public static function register($key, $value, $lock = false)
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
     * get registry by key
     * @param  string $key
     * @return object
     */
    public static function get($key = null)
    {

        if( is_null($key) ){
            return self::$registry;
        }
        else{
            if (!isset(self::$registry[$key])) {
                throw new Exception("There is no '{$key}' entry in the API registry!");
            }
            return self::$registry[$key];
        }

    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function getIterator()
    {
        return new ArrayObject($this->registry);
    }

}
