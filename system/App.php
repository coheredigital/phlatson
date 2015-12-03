<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
class App
{

    private $registry = array();
    private $lock = array();


    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    final public function api($name = null, $value = null)
    {
        if (!is_null($name) && !is_null($value)) {
            // all APIs set this way are locked
            // return $this allows chaining
            Api::set($name, $value, true);
            return $this;
        }
        else{
           return Api::get($name);
        }


    }


    public function __get($name)
    {
        return Api::get($name);
    }


    public function __set($name, $value)
    {
        Api::set($name, $value, true);
    }



}
