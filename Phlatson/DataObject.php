<?php
namespace Phlatson;

abstract class DataObject extends PhlatsonObject
{

    const BASE_FILENAME = "data.json";
    const BASE_FOLDER = '';
    const BASE_URL = '';

    // main data container, holds data loaded from JSON file
    protected $data;


    public function __construct($path = null)
    {

		parent::__construct($path);

        $this->data = new JsonObject($this->file);
        
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
