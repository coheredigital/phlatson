<?php

namespace Phlatson;

class FolderCollection implements \ArrayAccess, \Countable
{
    protected App $app;
    protected Folder $parent;
    protected int $position = 0;
    protected array $collection = [];

    public function __construct(App $app, Folder $parent)
    {
        $this->app = $app;
        $this->parent = $parent;
    }

    public function append(string $name): self
    {
        if (!\file_exists($this->parent->path() . $name)) {
            throw new \Exception('Folder (' . $this->parent->path() . $name . ') does not exist, cannot append to collection.');
        }

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

    public function get($key): ?Folder
    {
        if (!$this->has($key)) {
            return null;
        }

        $folder = new Folder(
            $this->app,
            $this->collection[$key],
            $this->parent
        );

        return $folder;
    }

    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value): void
    {
        $this->collection[$key] = $value;
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
