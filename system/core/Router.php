<?php

class Router
{

    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

}
