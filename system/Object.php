<?php

abstract class Object extends Flatbed implements JsonSerializable
{

    use hookable;

    const DEFAULT_SAVE_FILE = "data.json";

    protected $file;
    protected $path;
    protected $rootFolder;
    protected $rootPath;

    // main data container, holds data loaded from JSON file
    protected $data = [];
    protected $initData = [];

    protected $defaultFields = ["name","template"];
    protected $skippedFields = ["name"];
    protected $route = [];

    protected $requiredElements = [];

    function __construct($file = null)
    {

        $this->file = $file;

        if (!is_null($this->file)) {

            $this->rootPath = $this->getRootPath();
            $this->path = $this->getPath();
            $this->data = $this->getData();
            $this->initData = $this->getData();
            $this->setUnformatted("name", $this->getName());
            $this->route = $this->getRoute();

            // set modified in data so it can be accessed like a field
            $this->setUnformatted("modified", filemtime($this->file));

        }


    }

    /**
     * @param $file
     * @return mixed
     * @throws FlatbedException
     *
     * return array of data from the passed in JSON file (ie: data.json)
     *
     */
    protected function getData(){
        $this->validateDataFile($this->file);
        return json_decode(file_get_contents($this->file), true);
    }

    protected function validateDataFile($file){
        if(!is_file($file)){
            throw new FlatbedException("Ivalid file ($file) passed for $this->className");
        }

    }

    public function getName()
    {
        $path = $this->getPath();
        $name = basename($path);
        return $name;
    }

    /**
     * @return string full system path (ie: C:/root/site/etc/)
     *
     * Determine rootPAth of this Object type from the Objects $file
     *
     */
    public function getRootPath()
    {

        $rootDirectory = "site/";
        if($this->isSystem()){
            $rootDirectory = "system/";
        }

        $path = $this->api('config')->paths->root . $rootDirectory . $this->rootFolder;
        $path = Filter::path($path);
        return $path;
    }

    /**
     * @return bool
     *
     * Determine if Object is a system object
     *
     */
    public function isSystem()
    {
        // remove site root from path
        $path = str_replace($this->api('config')->paths->root, "", $this->file);
        $path = Filter::uri($path);

        // get first part of path
        $pathParts = explode("/", $path);

        if($pathParts[0] == "system") return true;
        return false;
    }

    public function getPath()
    {
        $path = Filter::path(str_replace(static::DEFAULT_SAVE_FILE, "", $this->file));
        return $path;
    }


    /**
     * @return string
     *
     * Directory refers to the rootPath relative folder
     *
     */
    public function getDirectory()
    {

        $remove = [
            $this->rootPath,
            static::DEFAULT_SAVE_FILE
        ];

        $path = str_replace($remove, "", $this->file);
        $path = Filter::uri($path);

        return $path;
    }

    /**
     * @return array
     */
    protected function getRoute()
    {

        // first get root relative path
        $directory = $this->getDirectory();

        // break the path into it's parts an return the resulting array
        return explode("/", $directory);
    }

    /**
     * @return string
     *
     * Get the public accessible url for this Object
     *
     */
    protected function getUrl(){

        
        $rootPath = $this->api("config")->paths->root;

        $replace = [
            ROOT_PATH,
            $this->api("config")->paths->root,
            "data.json"
        ];
        $url = trim(str_replace($replace, "", $this->path), "/");
        $url = Filter::url($url);
        $url = "/$url";

        return $url;
    }

    protected function getFormatted($name)
    {

        // get raw value

        if(!$value = $this->getUnformatted($name)) return null;

        // get the field object matching the passed "$name"
        $field = $this->api("fields")->get($name);

        if ($field instanceof Field) {

            $fieldtypeName = $field->getUnformatted("fieldtype");
            $fieldtype = $this->api("extensions")->get($fieldtypeName);
            $fieldtype->object = $this;
            $fieldtype->value = $value;
            $value = $fieldtype->getOutput($value);

        }

        return $value;
    }


    protected function setFormatted($name, $value)
    {
        // get the field object matching the passed "$name"
        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed

        if ($field instanceof Field) {

            $fieldtype = $field->fieldtype;
            $fieldtype->object = $this;

            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getSave($value);
            }

        }

        $this->setUnformatted($name, $value);

    }


    /**
     * set value directly to $this->data[$name]
     * skips validation of passed value
     * should not generally be used on public facing API
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function setUnformatted($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * get value directly to $this->data[$name]
     * skips formatting of passed value
     *
     * @param  string $name
     * @return mixed
     */
    public function getUnformatted($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }


    protected function processSavePath()
    {

        // handle new object creation
        if ($this->isNew()) {
            // TODO - validate parent

            if (!$this->parent instanceof Page) {
                throw new FlatbedException("cannot create new page without valid parent");
            }

            $this->path = $this->parent->path . $this->name . "/";

            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }


    }

    protected function saveToFile($path, $filename)
    {
        $file = $path . $filename;
        $json = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($file, $json);
    }

    public function _save()
    {

        // can't edit system objects
        if( !$this->isEditable() ) return false;



        foreach($this->skippedFields as $fieldname){

            if($this->has($fieldname)) unset($this->data[$key]);

        }

        $this->processSavePath();

        $this->saveToFile($this->path, static::DEFAULT_SAVE_FILE);

        return $this;

    }

    public function _rename($name)
    {

        if ($name == $this->name) {
            return false;
        }

        $current = $this->path;
        $destination = $this->parent->path . $name . "/";

        rename($current, $destination);

        $this->path = $destination;
        $this->file = $this->path . static::DEFAULT_SAVE_FILE;

        return $this;

    }


    public function _delete()
    {

        // recursively remove all child file and folders before removing self
        $it = new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($this->path);
    }

    /**
     * @return bool
     *
     * Used to check if the page exist in the filesystem yet
     *
     */
    public function isNew()
    {
        return !is_file($this->file);
    }


    /**
     * @return bool
     */
    public function isEditable()
    {
        if($this->isSystem()) return false;
        return true;
    }


    /**
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }


    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }



    protected function checkDataIntegrity()
    {

        foreach ($this->requiredElements as $name) {

            if (!$this->has($name)) {
                throw new FlatbedException(" Cannot continue: missing '$name' in $this '$this->name' ($this->file) from required elements (" . implode(", ", $this->requiredElements) . ").");
            }

        }

    }


    public function get($name)
    {
        switch ($name) {
            case 'directory':
                return Filter::uri($this->getName());
            case 'url':
                return $this->getUrl();
            case 'urlEdit':
                // TODO: temp solution for save redirect (maybe add via a hook)
                $url = $this->api('admin')->route->url . $this->rootFolder . "/edit/" . $this->getDirectory();
                return $url;
            case 'path':
                return $this->{$name};
//            case 'modified':
//                $time = filemtime($this->file);
//                return DateTime::createFromFormat("U", $time);
            case 'className':
                return get_class($this);
            case 'defaultFields':
                return $this->defaultFields;
            default:
                return $this->getFormatted($name);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        $this->setFormatted($name, $value);
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }


    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __toString()
    {
        return $this->className;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

}
