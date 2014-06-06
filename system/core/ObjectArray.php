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


    public function filter($array){

        $objects = $this->all();

        foreach ($array as $key => $value) {

            if ( !$object->{$key}) continue;

            $objects = array_filter($objects, function($extension){
                return $object->{$key} == $value;
            });

        }
        return $objects;
    }


    /**
     * return first item in data array
     */
    public function first(){
        return $this->data[0];
    }

    /**
     * return item at given index
     */
    public function index($x){
        return $this->data[$x];
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
