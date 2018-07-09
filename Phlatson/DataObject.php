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

    protected $template;
    protected $rootPath;

    public function __construct($path = null)
    {
        parent::__construct($path);

        if ($path) {
            // normalize
            $file = ROOT_PATH . 'site/' . $this::BASE_FOLDER . trim($path, '/') . '/' . $this::BASE_FILENAME;
            if (file_exists($file)) {
                $this->data = new JsonObject($file);
            }
        }

        // return if no data set (this is a new page)
        // the follow could initializes existing pages
        if (!$this->data) {
            return;
        }
        if ($this->data('template')) {
            $this->template = new Template($this->data('template'));
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
                return $this->template;
            default:
                if ($this->data && $this->data($key)) {
                    return $this->data($key);
                }
                break;
        }

        return parent::get($key);
    }
}
