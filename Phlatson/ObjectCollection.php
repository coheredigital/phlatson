<?php

namespace Phlatson;

class ObjectCollection extends Phlatson implements \IteratorAggregate, \Countable
{

    public $iterator;
    protected $paginate = false;
    protected $currentPage = 0;
    protected $limit = 0;
    protected $collection = [];


    public function append(PhlatsonObject $object)
    {
        $this->collection += [$object->name => $object];
        return $this;
    }

    public function count()
    {
        return $this->getIterator()->count();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

}
