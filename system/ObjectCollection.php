<?php

class ObjectCollection implements IteratorAggregate, ArrayAccess, Countable
{

    protected $object;
    protected $data = [];

    public function setObject($object)
    {
        $this->object = $object;
    }

    protected function isValidItem($item)
    {

        if ($item instanceof Object) {
            return true;
        }
        return false;

    }

    public function add($item)
    {

        $this->data[$item->name] = $item;
        return $this;

    }

    public function import($items)
    {
        if (!$items instanceof ObjectCollection) {
            return $this;
        }

        foreach ($items as $item) {

            if (!$this->isValidItem($item)) {
                continue;
            }
            $this->add($item);

        }

        return $this;
    }

    /**
     * @param $array
     * @return array
     *
     * filters $this->data by $key => $value set matches
     *
     * $key must match a valid parameter of $this (name, published, location, a field name, etc)
     *
     */
    // TODO: move filtering into its own class
    public function filter($array)
    {

        foreach ($array as $key => $value) {

            $objects = array_filter(
                $this->data,
                function ($object) use ($key, $value) {

                    if (!isset($object->{$key})) {
                        return false;
                    }
                    return $object->{$key} == $value;
                }
            );

        }
        $this->data = $objects;
        return $this;
    }

    public function sort($property, $direction = "ASC")
    {

        $value = $this->first()->get($property);
        $type = gettype($value);


        usort(
            $this->data,
            function ($a, $b) use ($property, $type) {
                $v1 = $a->get($property);
                $v2 = $b->get($property);

                switch ($type) {
                    case "integer":
                        if ($v1 == $v2) {
                            return 0;
                        }
                        return ($v1 < $v2) ? -1 : 1;
                    default:
                        return strcmp($v1, $v2);
                }

            }
        );

        if ($direction == "DESC") {
            $this->data = array_reverse($this->data);
        }
    }


    public function get($name)
    {
        if ($this->has($name)) {
            return $this->data[$name];
        }
    }


    public function has($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * return first item in data array
     */
    public function first()
    {
        return $this->index(0);
    }

    /**
     * return first item in data array
     */
    public function last()
    {
        return $this->index(-1);
    }

    /**
     * return item at given index
     */
    public function index($x)
    {
        return array_values($this->data)[$x];
    }

    public function getArray($key, $value)
    {

        $array = [];

        foreach ($this as $object) {
            $key = $object->get($key);
            $value = $object->get($value);

            $array[$key] = $value;
        }
        return $array;

    }


    /* Interface requirements */
    public function getIterator()
    {
        return new ArrayObject($this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetUnset($key)
    {
        return $this->remove($key);
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }
}
