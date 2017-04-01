<?php

class ObjectCollection extends Flatbed implements Iterator, ArrayAccess, Countable
{


    protected $currentIndex = 0;
    protected $startIndex = 0;
    protected $endIndex;

    protected $limit = 0;
    protected $pageCount;
    protected $currentPage;

    protected $isPaginated = false;

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

    public function add(Object $item)
    {
        $this->data += [$item->name => $item];
        return $this;
    }


    public function prepend(Object $item)
    {

        $this->data = [ $item->name => $item ] + $this->data;
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

    /**
     * @param $fieldname
     * @param string $direction
     * @return $this
     */
    public function sort($fieldname, $direction = "ASC")
    {

        $object = $this->first();

        if(!$value = $object->getUnformatted($fieldname)){
            throw new FlatbedException("Cannot sort by '$fieldname' no data by that name can be found in {$this}.");
        }

        $type = gettype($value);


        usort(
            $this->data,
            function ($a, $b) use ($fieldname, $type) {
                $v1 = $a->get($fieldname);
                $v2 = $b->get($fieldname);

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

        if ($direction == "DESC") $this->reverse();

        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function limit(int $limit) : self
    {
        if ($limit < 0) {
            throw new FlatbedException("Limit cannot be set to less than 0");
        }
        $this->limit = $limit;


        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function paginate(string $name = "") : self
    {

        if ($this->limit ===  0) {
            throw new FlatbedException("Cannot paginate results that have no set limt");
        }

        $this->isPaginated = true;
        // TODO : set $name to paramter, this is the get variable to use for paginating

        // determine the page count
        $count = $this->count();
        $this->pageCount = intval($count / $this->limit);
        if($count % $this->limit > 0) $this->pageCount++;


        if ( $this->api('request')->get->page && $this->limit ) {
            $this->currentPage = (int) $this->api('request')->get->page;
            $this->endIndex = $this->currentPage * $this->limit;
        }

        return $this;
    }




    /**
     * returns new collection with index range items
     * @return $this
     */
    public function slice(int $start, $end)
    {
        // TODO : implement non destructive slice
        return $this;
    }

    /**
     * reverses array orders
     * @return $this
     */
    public function reverse()
    {
        $this->data = array_reverse($this->data);
        return $this;
    }


    public function has($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * return first item in data array
     * @return Object
     */
    public function first()
    {
        return $this->index(0);
    }

    /**
     * return last item in data array
     * @return Object
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

        if(!count($this->data)){
            throw new FlatbedException("$this->className is empty, cannot retrieve index($x)");
        }
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
    public function rewind()
    {
        if ( $this->currentPage > 1 ) {
            $this->currentIndex = ($this->currentPage - 1) * $this->limit;
        }
        else {
            $this->currentIndex = 0;
        }

    }
    public function current()
    {
        return $this->index($this->currentIndex);
    }

    public function key()
    {
        return array_keys($this->data)[$this->currentIndex];
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function valid()
    {
        if ($this->limit > 0 && $this->currentIndex === $this->endIndex) return false;
        return array_key_exists( $this->key() , $this->data );
    }
    /* Interface requirements */



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



    public function get($name)
    {
        switch ($name) {
            case 'className':
                return get_class($this);
            case 'count':
                return $this->count();
            case 'limit':
                return $this->limit;

            default:
                return $this->data[$name];
        }

    }

    public function __get($name)
    {
        return $this->get($name);
    }


}
