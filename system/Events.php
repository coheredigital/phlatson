<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 05/02/2015
 * Time: 11:51 AM
 */

class Events {

    private $listeners = array();

    public function listen($event, $callback)
    {
        $this->listeners[$event][] = $callback;
    }

    public function dispatch($event, PublisherInterface $param)
    {
        foreach ($this->listeners[$event] as $listener)
        {
            call_user_func_array($listener, array($param));
        }
    }

}