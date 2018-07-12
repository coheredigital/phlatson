<?php

namespace Phlatson;

abstract class DataObject extends BaseObject
{
    const BASE_FILENAME = 'data.json';
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $data;
    protected $formattedData = [];

    public function __construct($path = null)
    {
        if (is_null($path)) {
            return;
        }
        $path = '/' . trim($path, '/') . '/';

        $classname = (new \ReflectionClass($this))->getShortName();

        $jsonData = $this->api('finder')->getTypeData($classname, $path);
        $this->setData($jsonData);
    }

    public function setData(JsonObject $data) : self
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

    public function exists() : bool
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
            case 'modified':
                $value = $this->data('modified');
                return new PhlatsonDateTime("@$value");
            case 'template':
                return $this->template();
            default:
                if ($this->data && $this->data($key)) {
                    return $this->data($key);
                }
                break;
        }

        return parent::get($key);
    }
}
