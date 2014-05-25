<?php

class Config extends DataContainer
{

    public function __construct()
    {
        $this->styles = new SimpleArray();
        $this->scripts = new SimpleArray();
    }

}