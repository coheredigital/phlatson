<?php
namespace Phlatson;


/**
 * 
 * Variable convention for Phlatson objects (Page, Field, Template, View)
 *      example for this case a Page, located at
 *      /site/pages/about-us/our-team/jane-doe/data.json
 * 
 *      $file = "/site/pages/about-us/our-team/jane-doe"
 *      the full path relative to the site root, minus filename
 * 
 *      $path = "/site/pages/about-us/our-team/jane-doe"
 *      the full path relative to the site root, minus filename
 * 
 *      $url = "/about-us/our-team/jane-doe"
 *      web accesible URL
 * 
 *      $folder = "/about-us/our-team/jane-doe"
 *      path relative to other objects of this type
 * 
 *      $name = "jane-doe"
 *      the base name of the path  : /page
 * 
 */

abstract class BaseObject extends Phlatson
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


    protected function folder(string $path)
    {
        $value = \str_replace(ROOT_PATH, '', $path);
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    protected function path($file)
    {

        if (!is_file($file)) {
            throw new Exceptions\PhlatsonException("Cannot get path of $file");
        }

        $value = dirname($file) . "/";
        return $value;
    }

    protected function url($path)
    {
        // remove root from path
        $value = \str_replace($this->rootPath, '', $path);
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    public function get(string $key)
    {
        switch ($key) {
            case 'name':
                return \basename($this->path);
            case 'file':
                return $this->data->file;
            case 'filename':
                return basename($this->file);
            case 'filepath':
            case 'path':
                return $this->path($this->file);
            case 'folder':
                return $this->url($this->data->path);
            case 'url':
                return $this->url($this->data->path);
            default:
                return null;
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
