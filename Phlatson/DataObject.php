<?php

namespace Phlatson;


/**
 *
 * Variable convention for Phlatson objects (Page, Field, Template, View)
 *
 *      example for this case a Page, located at
 *      /site/pages/about-us/our-team/jane-doe/data.json
 *
 *      $file = "/site/pages/about-us/our-team/jane-doe/data.json"
 *      the full path relative to the site root including filename
 *
 *      $path = "/site/pages/about-us/our-team/jane-doe"
 *      the full path relative to the site root, minus filename
 *
 *      $url = "/about-us/our-team/jane-doe"
 *      web accessible URL
 *
 *      $folder = "/about-us/our-team/jane-doe"
 *      path relative to other objects of this type
 *
 *      $name = "jane-doe"
 *      the base name of the path  : /page
 *
 */

abstract class DataObject
{

    use ApiAccess;

    protected JsonObject $data;
    protected array $formattedData  = [];
    protected FieldCollection $fields;
    protected string $rootPath;
    protected ?Template $template = null;

    public function __construct($path = null)
    {
        if (is_null($path)) {
            return;
        }
        $path = '/' . trim($path, '/') . '/';

        $classname = $this->classname();

        $jsonData = $this->api('finder')->getDataFor($classname, $path);
        $this->setData($jsonData);
    }

    public function setData(JsonObject $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function template(): Template
    {
        if (!$this->template && $name = $this->data->get('template')) {
            $this->template = $this->api('finder')->get("Template", $name);
        }
        return $this->template;
    }

    public function exists(): bool
    {
        return file_exists($this->file);
    }

    public function rootFolder(): string
    {
        $value = str_replace($this->name(), '', $this->folder());
        $value = trim($value, "/");
        return "/$value/";
    }

    public function folder(): string
    {
        $value = \str_replace(ROOT_PATH, '', $this->path());
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    public function file(): string
    {
        return $this->data->file;
    }

    public function rootPath(): string
    {
        return rtrim(ROOT_PATH . "site/" . $this::BASE_FOLDER, '/') . '/';
    }

    protected function rootUrl(): string
    {

        $url = $this->url();
        $url = trim($url, "/");
        $url = str_replace($this->name(), "", $url);
        $url = trim($url, "/");

        if (!$url) {
            return "/";
        }
        return "/$url/";
    }

    public function path(): string
    {
        $file = $this->file();
        if (!is_file($file)) {
            throw new \Exception("Cannot get path of $file");
        }

        $value = dirname($file) . "/";
        return $value;
    }

    public function url(): string
    {
        return $this->folder();
    }

    public function name(): string
    {
        return \basename($this->path());
    }

    public function filename(): string
    {
        return basename($this->file);
    }

    public function save(): string
    {
        return basename($this->file);
    }

    /**
     * Retrieve raw unformatted data from the data object
     * if not $key is provided returns the entire data object
     *
     * @param string $key
     * @return mixed
     */
    public function data(?string $key = null)
    {
        return $key ? $this->data->get($key) : $this->data;
    }

    public function get(string $key)
    {
        $value = null;

        $value = $this->data->get($key);

        if ($this->data->get($key)) {
            $field = $this->api('finder')->get("Field", $key);
            $fieldtype = $field->type();
            $value = $fieldtype->decode($value);
        }



        return $value ?: null;
    }

    /**
     * Magic method mapped the self::get() primarily for readability
     * example
     * <?= $page->title ?>
     * instead of
     * <?= $page->get('title') ?>
     *
     * @param string $key
     * @return void
     */
    final public function __get(string $key)
    {
        return $this->get($key);
    }
}
