<?php

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */
class App
{

    protected $registry;

    public function __construct(Registry $registry){
        $this->registry = $registry;
    }

    public function service($name, $object = null){
        if(is_null($object)){
            return $this->registry->get($name);
        }

        $this->registry->set($name, $object);

    }

}
