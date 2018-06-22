<?php
namespace Phlatson;

abstract class PhlatsonObject extends Phlatson
{

    const DEFAULT_SAVE_FILE = "data.json";
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $data;
    protected $template;

    public function __construct($path = null)
    {

        if ($path) {
            // normalize
            $path = trim($path, "/") . "/";
            $filepath = '/site/' . $this::BASE_FOLDER . $path . $this::DEFAULT_SAVE_FILE;
            $this->data = new JsonObject($filepath);
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
            case 'path':
                $value = $this->data->path;
                break;
            case 'url':
                $value = $this->data->path;
                $value = \str_replace(SITE_PATH, '', $value);
                $value = \str_replace($this::BASE_FOLDER, '', $value);
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
