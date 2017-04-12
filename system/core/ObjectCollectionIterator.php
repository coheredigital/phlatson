<?php

class ObjectCollectionIterator implements Iterator, ArrayAccess, Countable
{


    protected $currentIndex = 0;
    protected $startIndex = 0;
    protected $endIndex;

    protected $limit = 0;
    protected $pageCount;
    protected $currentPage;

    protected $isPaginated = false;

    protected $collection = [];

    protected $filter;


    public function append(Object $item)
    {
        $this->collection += [$item->name => $item];
        return $this;
    }

    public function prepend(Object $item)
    {

        $this->collection = [ $item->name => $item ] + $this->collection;
        return $this;
    }

    public function import(ObjectCollection $items)
    {
        foreach ($items as $item) {
            if (!$this->isValidItem($item)) {
                continue;
            }
            $this->append($item);
        }
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
            $this->collection,
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
        $this->endIndex = $this->limit - 1;

        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function paginate($pageNumber) : self
    {

        if ($this->limit ===  0) {
            throw new FlatbedException("Must set a limit on ObjectCollection before pagination can be used.");
        }

        $this->isPaginated = true;
        // TODO : set $name to paramter, this is the get variable to use for paginating

        // determine the page count
        $count = $this->count();
        $this->pageCount = intval($count / $this->limit);
        if($count % $this->limit > 0) $this->pageCount++;

        $this->currentPage = 1;

        // overwrite current page base on request page
        if ( $pageNumber && $this->limit ) {
            $this->currentPage = $pageNumber;
        }

        $this->endIndex = $this->currentPage * $this->limit;

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
        $this->collection = array_reverse($this->collection);
        return $this;
    }


    public function has($name)
    {
        return isset($this->collection[$name]);
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

        if(!count($this->collection)){
            throw new FlatbedException("$this->className is empty, cannot retrieve index($x)");
        }
        return array_values($this->collection)[$x];
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
        return array_keys($this->collection)[$this->currentIndex];
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function valid()
    {
        if ($this->limit > 0 && $this->currentIndex === ($this->endIndex + 1)) return false;

        return array_key_exists( $this->key() , $this->collection );
    }
    /* Interface requirements */

    /**
     * simply return the count of elments in the data container
     * @return int
     */
    public function count() : int
    {
        return (int) count($this->collection);
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
            case 'currentPage':
            case 'pageCount':
                return $this->{$name};
            case 'nextPage':
                return $this->currentPage + 1;
            case 'previousPage':
                return $this->currentPage - 1;
            default:
                return $this->collection[$name];
        }

    }

    public function __get($name)
    {
        return $this->get($name);
    }


}
