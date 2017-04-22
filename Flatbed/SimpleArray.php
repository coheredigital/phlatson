<?php
namespace Flatbed;
/*

Basic data array to access simple arrays like object and provide common functionality in the api
may not be needed long term, possible that Objects could extend from this to simplify the methods in
Objects and allow natural fall-backs

 */

class SimpleArray implements \IteratorAggregate
{

    protected $data = [];


    public function add($name)
    {
        $key = $this->getKey($name);
        $this->data[$key] = $name;
        return $this;
    }

    public function prepend($name)
    {
        $key = $this->getKey($name);
        $this->data = [$key => $name] + $this->data;
        return $this;
    }

    public function append($name)
    {
        $key = $this->getKey($name);
        $this->data = $this->data + [$key => $name];
        return $this;
    }

    public function import($array = null)
    {

        if (is_array($array)) {
            array_merge($this->data, $array);
        }
        return $this;
    }


    protected function getKey($filename)
    {
        $pos = strpos($filename, '?');
        $key = $pos ? substr($filename, 0, $pos) : $filename;
        return md5($key);
    }


    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }


    public function __get($key)
    {
        return $this->get($key);
    }

    public function get($key)
    {
        if (is_object($key)) {
            $key = "$key";
        } // stringify $object
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return false;
    }

    public function has($key)
    {
        return ($this->get($key) !== null);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayObject($this->data);
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

}
