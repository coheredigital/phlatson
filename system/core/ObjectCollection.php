<?php

class ObjectCollection extends Flatbed implements IteratorAggregate, ArrayAccess, Countable
{

    public $iterator;
    protected $paginate = false;
    protected $currentPage = 0;
    protected $limit = 0;

    public function __construct()
    {
        $this->iterator = new ObjectCollectionIterator();
        $this->iterator = new ObjectCollectionFilter( $this->iterator, null );
    }

    // public function setObject($object)
    // {
    //     $this->object = $object;
    // }

    protected function isValidItem($item)
    {
        if ($item instanceof Object) {
            return true;
        }
        return false;
    }

    public function append(Object $item)
    {
        $this->getIterator()->append($item);
        return $this;
    }


    public function prepend(Object $item)
    {
        $this->getIterator()->prepend($item);
        return $this;
    }

    public function import($items)
    {
        $this->getIterator()->import($items);
        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function limit(int $limit) : self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function paginate(string $name = "page") : self
    {

        $requestedPageNumber = (int) $this->api('request')->get->{$name};

        $this->paginate = true;
        $this->currentPage = $requestedPageNumber > 0 ? $requestedPageNumber : 1;
        return $this;
    }



    /**
     * returns new collection with index range items
     * @return $this
     */
    public function slice(int $start, $end) : self
    {
        // TODO : implement non destructive slice
        return $this;
    }

    /**
     * reverses array orders
     * @return $this
     */
    public function reverse() : self
    {
        $this->data = array_reverse($this->data);
        return $this;
    }


    public function has($name)
    {
        return $this->getIterator()->has($name);
    }

    /**
     * return first item in data array
     * @return Object
     */
    public function first()
    {
        return $this->getIterator()->first();
    }

    /**
     * return last item in data array
     * @return Object
     */
    public function last()
    {
        return $this->getIterator()->last();
    }

    /**
     * return item at given index
     */
    public function index($x)
    {

        return $this->getIterator()->index($x);
    }

    public function getArray()
    {
        return $this->getIterator()->getArray();
    }


    /** 
     * hands of iteration resposabiliies to the ObjectCollectionIterator class
     * @return ObjectCollectionIterator 
     */
    public function getIterator() : Iterator
    {
        if($this->limit > 0 && !$this->iterator instanceof ObjectCollectionPagination) {
            $offset = $this->paginate ? ($this->currentPage * $this->limit) - $this->limit : 0;
            $this->iterator = new ObjectCollectionPagination($this->iterator, $offset, $this->limit);
        }
        return $this->iterator;
    }


    /**
     * simply return the count of elments in the data container
     * @return int
     */
    public function count() : int
    {
        // NOTE : if FilterIterator gets applied at "getIterator" time, this will only return count within limit
        // this would add the requirement of a "countTotal" method or something similar
        return iterator_count($this->getIterator());
    }

    public function offsetSet($key, $value)
    {
        $this->getIterator()->set($key, $value);
    }

    public function offsetGet($key)
    {
        return $this->getIterator()->get($key);
    }

    public function offsetUnset($key)
    {
        return $this->getIterator()->remove($key);
    }

    public function offsetExists($key)
    {
        return $this->getIterator()->has($key);
    }

    public function get($name)
    {
        switch ($name) {
            case 'className':
                return get_class($this);
            case 'count':
                return $this->count();

            // give access to collection values
            case 'limit':
                return $this->$name;
            
            // delegate get to Paginated collection
            case 'currentPage':
            case 'pageCount':
                return $this->paginate ? $this->getIterator()->get($name) : 0;
            case 'currentPage':
                return $this->currentPage ?? null;
            case 'nextPage':
                if ($this->currentPage > 0 && $this->currentPage < $this->pageCount) {
                    return $this->currentPage + 1;
                }
                return null;
            case 'previousPage':
                return $this->currentPage ? $this->currentPage - 1 : null;
            default:
                return $this->data[$name];
        }

    }

    public function __get($name) {
        return $this->get($name);
    }

}
