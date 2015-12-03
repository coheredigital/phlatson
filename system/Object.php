<?php

abstract class Object extends App implements JsonSerializable
{

    use hookable;

    const DEFAULT_SAVE_FILE = "data.json";

    protected $file;
    protected $path;
    protected $modified;
    protected $rootFolder;
    protected $rootPath;

    // main data container, holds data loaded from JSON file
    protected $data = [];
    protected $previousData = [];
    protected $settings = [];

    protected $defaultFields = [];
    protected $route = [];

    protected $requiredElements = [];

    function __construct($file = null)
    {

        $this->file = $file;

        if (!is_null($this->file)) {

            $this->rootPath = $this->getRootPath();
            $this->path = $this->getPath();
            $this->data = $this->getData();
            $this->setUnformatted("name", $this->getName());
            $this->route = $this->getRoute();

        }

        // $this->checkDataIntegrity();

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

        $replace = [ROOT_PATH,$rootPath, "data.json"];

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
        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed, if it is I should note the reason here

        if ($field instanceof Field) {

            $fieldtype = $this->api("extensions")->get($field->getFieldtypeName());
            $fieldtype->object = $this;
            $value = $fieldtype->getOutput($value);

        }

        return $value;
    }


    protected function setFormatted($name, $value)
    {
        // get the field object matching the passed "$name"
        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed

        if ($field instanceof Field) {

            $fieldtype = $field->type;
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
    protected function getUnformatted($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }


    protected function processInputData()
    {

        $post = $this->api("request")->post;

        // loop through the templates available fields so that we only set values
        // for available fields and ignore the rest

        $fields = $this->template->fields;

        // create new array for save data, this will inherently remove data values that do not have matching fields
        $data = [];

        foreach ($fields as $field) {
            $value = isset($post->{$field->name}) ? $post->{$field->name} : $this->getUnformatted("$field->name");
            $value = $field->type->getSave($value);
            $data[$field->name] = $value;
        }

        $this->data = $data;

    }

    /**
     * Determine if Object name has changed or needs to be created for the first time
     */
    protected function processSaveName()
    {

        $post = $this->api("request")->post;

        // set name value
        if ($post->name && !$this->isNew()) { // TODO : this is temp
            $currentName = $this->name;
            $newName = Filter::name($post->name); // TODO add page name sanitizer
            if($currentName != $newName){
                $this->api("logger")->add("notice","Page '$this->name' renamed to '$newName'");
                unset($this->data["name"]);
                $this->rename($newName);
            }



        } else if($this->isNew()) { // generate page name from defined field
            $template = $this->template;
            $nameFieldReference = $template->settings['nameFrom'];
            $name = $this->get($nameFieldReference);
            $name = Filter::name($name);
            $this->name = $name;// TODO:  refactor code to allow name setting and getting to be handled the same way as other fields (by the Fieldtype class associated with it)
        }

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
        // unset parent value in data container
        unset($this->data["parent"]);

    }

    protected function saveFile($path, $filename)
    {

        file_put_contents(
            $path . $filename,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );

    }

    public function _save()
    {

        // block editing of any system Objects
        if($this->isSystem()) throw new FlatbedException("Cannot edit system $this->className ($this->name), duplicate in site folder to makes changes");


        // can't edit system objects
        if($this->isEditable())

        // store objects existing data for reference
        $this->previousData = $this->data;

        if ($this->api("config")->simulate) {
            $saveFile = "test.json";
        } else {
            $saveFile = static::DEFAULT_SAVE_FILE;
        }

        $this->processSaveName();
        $this->processSavePath();
        $this->processInputData();
        $this->saveFile($this->path, $saveFile);

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
    public function isViewable()
    {
        if($this->isSystem()) return false;
        if(!$this instanceof Page) return false;
        if(!is_file($this->layout)) return false;
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
                // TODO: temp solution for save redirect
                $url = $this->api('admin')->route->url . $this->rootFolder . "/edit/" . $this->getDirectory();
                return $url;
            case 'path':
                return $this->{$name};
            case 'modified':
                return filemtime($this->file);
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
