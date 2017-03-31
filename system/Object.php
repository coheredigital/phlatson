<?php

abstract class Object extends Flatbed implements JsonSerializable
{

    use hookable;

    const DEFAULT_SAVE_FILE = "data.json";

    protected $file;
    protected $root;

    protected $rootFolder;
    protected $rootPath;

    // main data container, holds data loaded from JSON file
    protected $data = [];

    protected $defaultFields = ["name","template"];
    protected $skippedFields = ["name"];

    protected $lockedFields = [];

    protected $requiredElements = [];

    public function __construct($file = null)
    {

        $this->rootPath = $this->getRootPath();

        if ($file) {

            if (!file_exists($file)) {
                throw new FlatbedException("Invalid file passed to {$this->className} class.");
            }

            $this->file = $file;

            $this->data = $this->getData();
            $this->initData = $this->data;
            $this->setUnformatted("name", $this->getName());

            // set modified in data so it can be accessed like a field
            $this->setUnformatted("modified", filemtime($this->file));

        }
        else{
            $this->root = $this->api('config')->paths->site . $this->rootFolder;
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
        return basename($this->getPath());
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




    /**
     * @return array
     */
    protected function directoryParts()
    {
        return explode("/", $this->getDirectory());
    }

    /**
     * @return string
     *
     * Directory refers to the rootPath relative folder
     *
     */
    public function getDirectory()
    {

        // strip array parts
        $remove = [
            $this->rootPath,
            static::DEFAULT_SAVE_FILE
        ];

        $path = str_replace($remove, "", $this->file);
        return Filter::uri($path);
    }


    /**
     * @return string  path to the current object data file
     */
    public function getPath(): string
    {

        $path = '';
        if ( is_file( $this->file ) ) {
            $path = dirname($this->file);
        }
        else{
            $path = $this->rootPath . $this->name;
        }

        return Filter::path($path);
        return $path;
    }


    /**
     * @return string  path to the current object data file
     */
    public function getModified()
    {
        $time = filemtime($this->file);
        $datetime = FlatbedDateTime::createFromFormat("U", $time);
        return $datetime;
    }



    /**
     * @return  string  the public accessible url for this Object
     *
     */
    protected function getUrl(){

        // get the site root
        $rootPath = $this->api("config")->paths->root;

        // remove the ROOT_PATH, site root, and data.json from the object path to get a relative directory
        $replace = [
            ROOT_PATH,
            $this->api("config")->paths->root,
            "data.json"
        ];

        $url = str_replace( $replace, "", $this->getPath());

        // $url = trim( $url , "/");

        return Filter::url($url);
    }

    protected function getFormatted($name)
    {

        // get raw value

        if(!$value = $this->getUnformatted($name)) return null;

        // get the field object matching the passed "$name"
        if ($this->api("fields")) {
            $field = $this->api("fields")->get($name);
        }
        // use field formatting if instance of field is available and API extensions is accesible
        // TODO: extensions should always be available
        if ($field instanceof Field && $this->api("extensions")) {

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

    /**
     * @return bool
     *
     * Used to check if the page exist in the filesystem yet
     *
     */
    public function isNew()
    {
        return !file_exists($this->file);
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


    public function get( string $name)
    {
        switch ($name) {
            case 'name':
                return $this->getName();
            case 'uri':
            case 'directory':
                return $this->getDirectory();
            case 'url':
                return $this->getUrl();
            case 'path':
                return $this->getPath();
            case 'urlEdit':
                // TODO: temp solution for save redirect (maybe add via a hook)
                $url = $this->api('admin')->route->url . $this->rootFolder . "/edit/" . $this->getDirectory();
                return $url;
           case 'modified':
               return $this->getModified();
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

    public function set( string $name, $value)
    {
        $this->setFormatted($name, $value);
        return $this;
    }

    /**
     * setter magic method
     * @param string $name
     * @param mixed $value
     */
    public function __set( string $name, $value)
    {
        $this->set($name, $value);
    }


    public function __isset( string $name)
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
