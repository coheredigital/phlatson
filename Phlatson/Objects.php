<?php
namespace Phlatson;

abstract class Objects extends Phlatson
{

    const SINGULAR_CLASSNAME = '';
    const BASE_FOLDER = '/';

    // $attributes array propeties will be made 
    // public via $this->get($key) but cannot be
    // set via $this->set($key,$value);
    public $attributes = [];

    protected $systemUrl;
    protected $systemPath;

    // the folder within the site and system paths to check for items ex: fields, templates, etc
    protected $rootFolder;

    // used to identify the singular version $this relates to
    protected $singularName;

    public function __construct()
    {
        // store paths and urls
        $this->attributes['path'] = ROOT_PATH . "site" . DIRECTORY_SEPARATOR . $this::BASE_FOLDER;
        $this->attributes['url'] = "/site/" . $this::BASE_FOLDER;
    }

    /**
     * give property access to all get() variables
     * @param  string $name
     * @return mixed
     */
    final public function __get( string $key)
    {
        return $this->attributes[$key] ?? false;
    }


    /**
     * check if the Singular Object type exists
     * 
     * @param  string $name the name or uri that points to the object relative to its storage folder
     * @return bool
     */
    public function has(string $uri) : bool
    {



        // get the file if it exists
        // if ($this->getDataFile($uri)) {
        //     return true;
        // }
        return false;
    }


    /**
     * get the singular object type by it uri/name
     * @param  string $name the name or uri that points to the object relative to its storage folder
     * @return Object
     */
    public function get(string $url) : ?Object
    {

        $path = $this->path . $url;

        // get the file if it exists
        if (!file_exists($path)) {
            return null;
        }

        $classname = __NAMESPACE__ . "\\" . $this::SINGULAR_CLASSNAME;
        return new $classname($url);

    }

}
