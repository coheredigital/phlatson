<?php
namespace Phlatson;

abstract class PhlatsonObject extends Phlatson
{

    const BASE_FILENAME = "data.json";
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $rootPath;

    public function __construct($path = null)
    {
        $this->rootPath = trim(DATA_PATH . $this::BASE_FOLDER, "/") . "/";
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
                $value = $this->data->getModifiedTime();
                $value = new PhlatsonDateTime("@$value");
                break;
            default:
                $value = $this->data->get($key);
                break;
        }

        return $value;

    }

    /**
     * Magic method mappaed the self::get() primarily for
     * syntactical template reasons 
     * example
     * <?= $page->title ?>
     * instead of 
     * <?= $page->get('title') ?>
     *
     * @param string $key
     * @return void
     */
    final public function __get (string $key) {
        return $this->get($key);
    }

}
