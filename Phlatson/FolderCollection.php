<?php

namespace Phlatson;

class FolderCollection implements \Iterator, \Countable
{
    public $iterator;
    protected App $app;
    protected int $position = 0;
    protected array $collection = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function append(Folder $folder): self
    {
        $this->collection[$folder->name()] = $folder;

        return $this;
    }

    public function reverse(): self
    {
        $this->collection = array_reverse($this->collection);

        return $this;
    }

    public function first(): Folder
    {
        return reset($this->collection);
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
            $item = $this->app->getPage($item);
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
