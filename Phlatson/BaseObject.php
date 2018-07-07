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

    public function get(string $key)
    {
        switch ($key) {
            case 'name':
                $value = \basename($this->path);
                break;
            case 'file':
                $value = $this->data->file;
                break;
            case 'filename':
                $value = basename($this->file);
                break;
            case 'filepath':
            case 'path':
                $value = dirname($this->file) . "/";
                break;
            case 'folder':
                $value = $this->data->path;
                $value = \str_replace(ROOT_PATH, '', $value);
                $value = trim($value, "/");
                $value = $value ? "/$value/" : "/";
                break;
            case 'url':
                $value = $this->data->path;
                $value = \str_replace($this->rootPath, '', $value);
                $value = trim($value, "/");
                $value = $value ? "/$value/" : "/";
                break;
            default:
                $value = null;
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
