<?php
namespace Phlatson;
/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
final class Api
{

    private static $registry = [];
    private static $lock = [];

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
    public static function set($key, $value)
    {

        if (isset(static::$registry[$key]) && in_array($key, static::$lock)) {
            throw new \Exception("There is already an API entry for '{$key}', value is locked.");
        }
        // set $key and $value the same to avoid duplicates
        static::$lock[$key] = $key;
        static::$registry[$key] = $value;

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

    protected static function has($name)
    {
        return array_key_exists($name, static::$registry);
    }

    public static function get($name) : ?object
    {
        if (!static::has($name)) return null;

        if (!is_object(static::$registry[$name])) {
            static::instantiate($name);
        }
        return static::$registry[$name];

    }

}