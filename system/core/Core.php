<?php

/*

Core class to connect most other system classes, stores system wide api variables

 */

abstract class Core
{


    private $className = null;

    public function className()
    {

        if (!isset($this->className)) {
            $this->className = get_class($this);
        }
        return $this->className;

    }

    public function __toString()
    {
        return $this->className();
    }

}