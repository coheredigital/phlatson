<?php

namespace Phlatson;

abstract class DataObject extends BaseObject
{
    const BASE_FILENAME = 'data.json';
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $data;
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
        if ($templateName = $this->data->get('template')) {
            $this->template = new Template($templateName);
        }
    }

    public function exists() : bool
    {
        return file_exists($this->file);
    }

    public function get(string $key)
    {
        switch ($key) {
            case 'modified':
                $value = $this->data->get('modified');
                $value = new PhlatsonDateTime("@$value");
                break;
            default:
                if ($this->data) {
                    $value = $this->data->get($key);
                }
                break;
        }

        if (!$value) {
            return parent::get($key);
        }

        return $value;
    }
}
