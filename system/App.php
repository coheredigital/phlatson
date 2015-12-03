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
    public function api($key = null, $value = null)
    {
        if ($value && $key) {
            
            $this->setApi($key, $value, true);
            return $this;
        }
        else if($key){
           return $this->getApi($key);
        }
        else{
            return $this->fetchAll();
        }
    }

    /**
     * @param $key
     * @param $value
     * @param bool $lock
     * @throws Exception
     */
    public function setApi($key, $value, $lock = true)
    {

        if (isset($this->registry[$key]) && in_array($key, $this->lock)) {
            throw new Exception("There is already an API entry for '{$key}', value is locked.");
        }
        // set $key and $value the same to avoid duplicates
        if ($lock) $this->lock[$key] = $key;
        $this->registry[$key] = $value;

    }

    /**
     * @param null $name
     * @return array
     * @throws Exception
     */
    public function __invoke($name = null, $object = null, $lock = true)
    {

        if (!is_null($name) && !is_null($object)) {
            $this->set($name, $object, $lock);
        } else {
            return $this->get($name);
        }

        return false;

    }


    public function fetchAll()
    {
        // instantiate an objects passed as string value
        foreach ($this->registry as $key => $value) {
            if (is_object($value)) continue;
            $this->instantiate($key);
        }
        return $this->registry;
    }

    public function instantiate($name)
    {
        if(!$classname = $this->registry[$name]) return null;
        $this->registry[$name] = new $classname;
    }

    protected function has($name)
    {
        return isset($this->registry[$name]);
    }

    public function getApi($name)
    {
        if (is_null($name))
            return $this->fetchAll();
        if (!$this->has($name)) {
            return false;
        }
        if (!is_object($this->registry[$name])) {
            $this->instantiate($name);
        }
        return $this->registry[$name];
    }

    public function __get($name)
    {
        $this->getApi($name);
    }


    public function __set($name, $value)
    {
        $this->setApi($name, $value);
    }


    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        switch ($name) {
            case 'api':
                return $this->api($arguments);
            default:
                return null;
        }
    }

}
