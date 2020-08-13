<?php

namespace Phlatson;

class FileCollection implements \ArrayAccess
{
    public $iterator;
    protected App $app;
    protected Folder $parent;
    protected int $currentPage = 1;
    protected int $limit = 0;
    protected int $position = 0;
    protected array $files = [];
    protected array $collection = [];

    public function __construct(App $app, Folder $parent)
    {
        $this->app = $app;
        $this->parent = $parent;
    }

    public function append($name): self
    {
        $this->collection[$name] = $name;

        return $this;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function has($key): bool
    {
        return isset($this->collection[$key]);
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        if (!$this->isReady($key)) {
            $this->instantiate($key);
        }

        return $this->collection[$key];
    }

    public function set($key, $value)
    {
        return $this->collection[$key] = $value;
    }

    /**
     * Check if the File has been instantiated yet
     *
     * @param string $key
     *
     * @return boolean
     */
    protected function isReady(string $key): bool
    {
        return !\is_string($this->collection[$key]);
    }

    protected function instantiate(string $key): self
    {
        $info = \pathinfo($this->collection[$key]);
        switch ($info['extension']) {
            case 'json':
                $this->set($key, new DataFile($this->parent->path() . $key, $this->parent));
                break;

            default:
                $this->set($key, new File($this->parent->path() . $key, $this->parent));
                break;
        }

        return $this;
    }

    /**
     * ArrayAccess methods
     *
     * @param string $key
     *
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key): void
    {
        unset($this->collection[$key]);
    }

    public function offsetExists($key): bool
    {
        return $this->has($key);
    }
}
