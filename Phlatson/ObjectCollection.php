<?php

namespace Phlatson;

abstract class ObjectCollection extends Phlatson implements \Iterator, \Countable
{

    public $iterator;
    protected $currentPage = 1;
    protected $limit = 0;
    protected $position = 0;

    protected $files = [];
    protected $collection = [];    

    public function append(PhlatsonObject $object)
    {
        if (!in_array($object->file)) {
            // files array ensures unique entries
            $this->files[] = $object->file;
            $this->collection[] = $object;
        }

        return $this;
    }



    public function limit(int $limit) : self
    {
        $this->limit = $limit;
        return $this;
    }

    public function paginate(int $currentPage) : self
    {
        if ($currentPage < 1) {
            throw new Exceptions\PhlatsonException("Request page number cannot be less than 1");
        }
        $this->currentPage = $currentPage;
        return $this;
    }

    public function count() : int
    {
        return count($collection);
    }
    
    public function index() : int
    {
        if ($this->limit > 0) {
            return ($this->currentPage * $this->limit) - $this->limit + $this->position;
        }
        else {
            return $this->position;
        }

    }


    /**
     * Iterator Interface methods
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->collection[$this->index()];
    }

    public function key()
    {
        return $this->index();
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        if ($this->limit && $this->position == $this->limit) {
            return false;
        }
        return isset($this->collection[$this->index()]);
    }

}
