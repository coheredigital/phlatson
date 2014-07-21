<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api::get() function defined in _functions.php
 */
final class Api
{

    private static $registry = array();
    private static $lock = array();


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
    public static function get($key = null)
    {

        if( is_null($key) ){
            return self::$registry;
        }

        if (isset(self::$registry[$key])) {
            return self::$registry[$key];
        }

        return false;


    }



}
