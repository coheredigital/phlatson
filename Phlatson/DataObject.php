<?php
namespace Phlatson;

abstract class DataObject extends PhlatsonObject
{

    const BASE_FILENAME = "data.json";
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
            $file = ROOT_PATH . 'site/' . $this::BASE_FOLDER . trim($path, "/") . DIRECTORY_SEPARATOR . $this::BASE_FILENAME;
            if (file_exists($file)) {
                $this->data = new JsonObject($file);
            }
        }



        // return if no data set (this is a new page)
        // the follow could initializes existing pages
        if (!$this->data) {
            return;
        }
        if ($templateName = $this->data->get("template")) {
            $this->template = new Template($templateName);
        }

    }

    public function get(string $key)
    {
        
        switch ($key) {
            case 'name':
                $value = \basename($this->data->path);
                break;
            case 'file':
                $value = $this->data->file;
                break;
            case 'path':
                $value = $this->data->path;
                break;
            case 'url':
                $value = $this->data->path;
                $value = \str_replace($this->rootPath, '', $value);
                $value = trim($value, "/");
                $value = $value ? "/$value/" : "/";
                break;
            case 'modified':
                if ($this->data) {
                    $value = $this->data->get('modified');
                }
                
                break;
            default:
                if ($this->data) {
                    $value = $this->data->get($key);
                }
                
                break;
        }

        return $value;

    }

}
