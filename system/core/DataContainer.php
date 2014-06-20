<?php

/*

Basic data array to access simple arrays like object and provide common functionality in the api
may not be needed long term, possible that Objects could extend from this to simplify the methods in
Objects and allow natural fallbacks

 */

abstract class DataContainer extends Core
{

    protected $data = array();


    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }


    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($key)
    {
        if (is_object($key)) {
            $key = "$key";
        } // stringify $object
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return false;
    }

    public function has($key)
    {
        return ($this->get($key) !== null);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
        return $this;
    }

    // allows the data array to be counted directly
    public function count()
    {
        return count($this->data);
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

}
