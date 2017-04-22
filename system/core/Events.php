<?php
namespace Flatbed;
class Events {

    private $listeners = array();

    public function listen($event, $callback)
    {
        $this->listeners[$event][] = $callback;
    }


    public function detach($event)
    {
       unset($this->listeners[$event]);
    }

    public function execute($event, Event $param)
    {

        if(!isset($this->listeners[$event])) return false;

        foreach ($this->listeners[$event] as $listener)
        {
            call_user_func_array($listener, array($param));
        }
    }

}