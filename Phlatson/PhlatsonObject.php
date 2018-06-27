<?php
namespace Phlatson;

abstract class PhlatsonObject extends Phlatson
{

    const BASE_FILENAME = "data.json";
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $data;
    protected $template;
    protected $file;
    protected $rootPath;

    public function __construct($path = null)
    {

        $this->rootPath = SITE_PATH . $this::BASE_FOLDER;

        if ($path) {
            // normalize
            $path = trim($path, "/") . "/";
            $this->file = '/site/' . $this::BASE_FOLDER . $path . $this::BASE_FILENAME;
            $this->data = new JsonObject($this->file);
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

    protected function getFile($path)
    {
        $path = trim($path, "/") . "/";
        $file = '/site/' . $this::BASE_FOLDER . $path . $this::BASE_FILENAME;
        
    }

    public function get(string $key)
    {
        
        switch ($key) {
            case 'name':
                $value = \basename($this->data->path);
                break;
            case 'path':
                $value = $this->data->path;
                break;
            case 'url':
                $value = $this->data->path;
                $value = \str_replace(SITE_PATH, '', $value);
                $value = \str_replace($this::BASE_FOLDER, '', $value);
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
    public function __get (string $key) {
        return $this->get($key);
    }

}
