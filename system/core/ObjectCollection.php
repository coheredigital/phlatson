<?php

class ObjectCollection extends Flatbed implements IteratorAggregate, ArrayAccess, Countable
{

    protected $object;

    // protected $iterator;
    protected $filter;
    protected $collection;

    public function __construct()
    {
        // $this->iterator = new ObjectCollectionIterator();
        $this->collection = new ObjectCollectionFilter(new ObjectCollectionIterator());
    }

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

    public function append(Object $item)
    {
        $this->collection->append($item);
        return $this;
    }


    public function prepend(Object $item)
    {

        $this->collection->prepend($item);
        return $this;

    }

    public function import($items)
    {
        $this->collection->import($items);
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
        $this->collection->limit( (int) $limit);
        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function paginate(string $name = "page") : self
    {
        $currentPage = (int) $this->api('request')->get->{$name};
        $this->collection->paginate($currentPage);
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
        return $this->collection->has($name);
    }

    /**
     * return first item in data array
     * @return Object
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * return last item in data array
     * @return Object
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * return item at given index
     */
    public function index($x)
    {

        return $this->collection->index($x);
    }

    public function getArray()
    {
        return $this->collection->getArray();
    }


    /** 
     * hands of iteration resposabiliies to the ObjectCollectionIterator class
     * @return ObjectCollectionIterator 
     */
    public function getIterator()
    {
        return $this->collection;
    }


    /**
     * simply return the count of elments in the data container
     * @return int
     */
    public function count() : int
    {
        return $this->collection->count();
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
                return $this->collection->{$name};
            case 'nextPage':
                return $this->collection->currentPage + 1;
            case 'previousPage':
                return $this->collection->currentPage - 1;
            default:
                return $this->data[$name];
        }

    }

    public function __get($name)
    {
        return $this->get($name);
    }


}
