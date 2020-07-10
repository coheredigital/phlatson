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

abstract class BaseObject extends Phlatson
{


    protected function rootFolder()
    {   
        $value = str_replace($this->name(), '', $this->folder());
        $value = trim($value, "/");
        return "/$value/";
    }

    protected function folder()
    {
        
        $value = \str_replace(ROOT_PATH, '', $this->path());
        $value = trim($value, "/");
        $value = $value ? "/$value/" : "/";
        return $value;
    }

    protected function file()
    {
        return $this->data->file;
    }

    protected function rootPath()
    {
        return rtrim(DATA_PATH . $this::BASE_FOLDER, '/') . '/';
    }

    protected function path()
    {
        $file = $this->file();
        if (!is_file($file)) {
            throw new \Exception("Cannot get path of $file");
        }

        $value = dirname($file) . "/";
        return $value;
    }

    protected function rootUrl()
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

    protected function url()
    {
        return $this->folder();
    }

    protected function name()
    {
        return \basename($this->path());
    }

    protected function filename()
    {
        return basename($this->file);
    }

    public function get(string $key)
    {
        switch ($key) {
            // publicly expose file properties
            case 'name':
            case 'file':
            case 'filename':
            case 'path':
            case 'rootPath':
            case 'rootFolder':
            case 'folder':
            case 'rootUrl':
            case 'url':
                return $this->{$key}();
                break;
            default:
                return null;
        }

        return $value;

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
    final public function __get (string $key) {
        return $this->get($key);
    }

}
