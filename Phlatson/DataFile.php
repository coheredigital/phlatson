<?php

namespace Phlatson;

// TODO: consider extending from File class
class DataFile
{
    public string $name;
    public string $path;
    public string $filename;
    public string $extension;
    public string $file; // TODO: make protected
    protected array $data;
    protected Folder $parent;

    public function __construct(?string $file = null, ?Folder $parent = null)
    {
        if (isset($file)) {
            $this->file = $file;
        }

        if (isset($file)) {
            $this->init();
        }

        if (isset($parent)) {
            $this->parent = $parent;
        }
    }

    protected function init(): void
    {
        if (!\file_exists($this->file)) {
            throw new \Exception("File ($this->file) does not exist");
        }
        $pathinfo = \pathinfo($this->file);
        $this->filename = $pathinfo['filename'];
        $this->path = $pathinfo['dirname'] . '/';
        $this->name = $pathinfo['basename'];
        $this->extension = $pathinfo['extension'];
    }

    protected function loadData(): void
    {
        if (!\file_exists($this->file)) {
            throw new \Exception("File ($this->file) does not exist");
        }
        $this->data = json_decode(file_get_contents($this->file), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Gets and returns a key from the data array.
     *
     * @return Mixed
     */
    public function get(string $key)
    {
        switch ($key) {
            default:
                return $this->data($key) ?? null;
                break;
        }
    }

    /**
     * Set the $key in $data array to the supplied $value.
     *
     * @param string $key
     * @param mixed  $value
     *
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function data(?string $key = null)
    {
        if (!isset($this->data)) {
            $this->loadData();
        }

        if (isset($key)) {
            return $this->data[$key] ?? null;
        }

        return $this->data;
    }

    public function merge(DataFile $json)
    {
        $this->data = array_replace_recursive($this->data(), $json->data());
    }

    public function save(): void
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        $file = $this->parent->path() . $this->name . '.json';
        file_put_contents($file, $json);
    }
}
