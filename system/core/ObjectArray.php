<?php

abstract class ObjectArray extends Core implements IteratorAggregate, ArrayAccess, Countable
{

    protected $data = array();



    protected function isValidItem($item){

        if($item instanceof Object){
            return true;
        }
        return false;

    }

    public function add($item) {

        if($item){
            $this->data[] = $item;
            return $this;
        }

    }

    public function import($items) {
        if(!is_array($items))return $this;

        foreach($items as $item){

            if(!$this->isValidItem($item)) continue;
            $this->add($item);

        }
        
        return $this;
    }


    /* Interface requirements */
    public function getIterator() {
        return new ArrayObject($this->data);
    }
    public function count() {
        return count($this->data);
    }
    public function offsetSet($key, $value) {
        $this->set($key, $value);
    }
    public function offsetGet($key) {
        return $this->get($key);
    }
    public function offsetUnset($key) {
        return $this->remove($key);
    }
    public function offsetExists($key) {
        return $this->has($key);
    }
}
