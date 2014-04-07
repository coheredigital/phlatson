<?php

class Config extends DataContainer
{

    public function __construct()
    {
        $this->styles = new FileArray();
        $this->scripts = new FileArray();
    }

}