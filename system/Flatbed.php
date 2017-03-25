<?php

/**
 * Root class that ties system together
 * Classes should extend Flatebed when they require access to one or more API variable
 */
class Flatbed
{

    private $registry = array();
    private $lock = array();


    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    final public function api(string $name = null, $value = null, bool $lock = false)
    {
        if (!is_null($name) && !is_null($value)) {
            // all APIs set this way are locked
            // return $this allows chaining
            Api::set($name, $value, $lock);
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
