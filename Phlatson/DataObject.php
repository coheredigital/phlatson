<?php

namespace Phlatson;

abstract class DataObject extends BaseObject
{

    protected JsonObject    $data;
    protected array         $formattedData  = [];
    protected array         $fields         = [];

    public function __construct($path = null)
    {
        if (is_null($path)) {
            return;
        }
        $path = '/' . trim($path, '/') . '/';

        $classname = $this->classname();

        $jsonData = $this->api('finder')->getData($classname, $path);
        $this->setData($jsonData);
    }

    public function setData(JsonObject $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function template()
    {
        if ($name = $this->data('template')) {
            return new Template($name);
        }
    }

    public function exists(): bool
    {
        return file_exists($this->file);
    }

    /**
     * Retreive raw data from the data object
     *
     * @param string $key
     * @return void
     */
    public function data(string $key)
    {
        return $this->data->get($key);
    }

    public function get(string $key)
    {
        switch ($key) {
            case 'template':
                return $this->template();
            default:
                if ($this->data && $this->data($key)) {
                    $finder = $this->api('finder');
                    $field = $finder->getType("Field", $key);
                }

                return $this->data($key);
        }

        return parent::get($key);
    }
}
