<?php

namespace Phlatson;

class ObjectCollection extends Phlatson implements \Iterator, \Countable
{

    public $iterator;
    protected $currentPage = 1;
    protected $limit = 0;
    protected $position = 0;

    protected $files = [];
    protected $collection = [];    

    public function append($item)
    {
        if ($item instanceof DataObject && !isset($this->files[$item->file])) {
            // files array ensures unique entries
            if (!$item->url) {
                return;
            }

            $this->files[$item->file] = true;
            $this->collection[] = $item->url;
        }
        else if(!isset($this->files[$item])) {
            $this->files[$item] = true;
            $this->collection[] = $item;
        }

        return $this;
    }

    public function reverse() {
        $this->collection = array_reverse($this->collection);
        return $this;
    }

    public function import(array $collection)
    {
        // TODO: this need work
        $files = array_fill_keys($collection, true);
        $this->files += $files;
        $this->collection += $collection;
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
            throw new \Exception("Request page number cannot be less than 1");
        }
        $this->currentPage = $currentPage;
        return $this;
    }

    public function count() : int
    {
        return count($this->collection);
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

    public function pageCount() : int
    {
        return (int) intval($this->count() / $this->limit) + (($this->count() / $this->limit) ? 1 : 0);
    }

    public function nextPage() : int
    {
        return (int) $this->currentPage + 1;
    }

    public function previousPage() : int
    {
        return (int) $this->currentPage - 1;
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

        $item = $this->collection[$this->index()];
        if (is_string($item)) {
            $item = new Page($item);
            // replace the existing pointer
            $this->collection[$this->index()] = $item;
        }

        return $item;
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
