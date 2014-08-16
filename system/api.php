<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
final class Api
{

    private static $registry = array();
    private static $lock = array();


    public static function __callStatic($name, $arguments)
    {
        if (is_null($name)) {
            return self::$registry;
        }

        if (isset(self::$registry[$name])) {
            return self::$registry[$name];
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
                return self::$registry;
            }

            if (isset(self::$registry[$key])) {
                return self::$registry[$key];
            }
        }


        return false;

    }


}
