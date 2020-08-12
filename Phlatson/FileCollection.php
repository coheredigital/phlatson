<?php

namespace Phlatson;

class FileCollection implements \Iterator, \Countable
{
    public $iterator;
    protected App $app;
    protected int $currentPage = 1;
    protected int $limit = 0;
    protected int $position = 0;
    protected array $files = [];
    protected array $collection = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function append($file): self
    {
        $this->collection[$file->name] = $file;

        return $this;
    }

    public function reverse(): self
    {
        $this->collection = array_reverse($this->collection);

        return $this;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function index(): int
    {
        if ($this->limit > 0) {
            return ($this->currentPage * $this->limit) - $this->limit + $this->position;
        } else {
            return $this->position;
        }
    }

    /**
     * Iterator Interface methods.
     *
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): DataObject
    {
        $item = $this->collection[$this->index()];
        if (is_string($item)) {
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
