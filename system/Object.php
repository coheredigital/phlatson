<?php

abstract class Object implements JsonSerializable
{

    const DEFAULT_SAVE_FILE = "data.json";

    public $name;
    protected $path;
    protected $configPath = null;
    protected $_file;
    protected $modified;
    protected $rootFolder;

    // main data container, holds data loaded from JSON file
    protected $data = array();
    protected $_settings = array();

    protected $defaultFields = array();
    protected $route = array();

    function __construct($file = null)
    {

        if (is_file($file)) {
            $this->_file = $file;
            $this->path = $this->getPath();
            $this->route = $this->getRoute();
            $this->data = json_decode(file_get_contents($this->_file), true);
            $this->name = $this->getName();
            $this->modified = filemtime($this->_file);
        }

    }


    protected function getName()
    {
        // set object name
        if ($this->isNew()) {
            return $this->getNewName();
        }
        $lastRequestIndex = count($this->route) - 1;
        return $this->route[$lastRequestIndex];
    }


    protected function getPath()
    {
        if (!$this->isNew()) {
            return normalizePath(str_replace(Object::DEFAULT_SAVE_FILE, "", $this->_file));
        } else {

        }
    }


    protected function getRootRelativePath()
    {

        $relativePath = str_replace(
            api("config")->paths->site . $this->rootFolder,
            "",
            $this->_file
        ); // trim the root path to get root relative path
        $relativePath = str_replace($this::DEFAULT_SAVE_FILE, "", $relativePath); // trim of file name to isolote path
        $relativePath = rtrim($relativePath, '/'); // trim excess slashes

        return $relativePath;
    }


    protected function getRoute()
    {

        $directory = $this->getRootRelativePath();
        $pathArray = explode("/", $directory);

        if (!count($pathArray)) {
            $pathArray[] = "$directory";
        }

        return $pathArray;
    }

    protected function getFormatted($name)
    {

        // get raw value
        $value = $this->getUnformatted($name);

        // get the field object matching the passed "$name"
        $field = api("fields") ? api("fields")->get($name) : false;
        if ($field) {
            $fieldtype = $field->type;

            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getOutput($value);
            }
        }

        return $value;
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
        return $this->data[$name] = $value;
    }

    /**
     * get value directly to $this->data[$name]
     * skips formatting of passed value
     * should not generally be used on public facing API
     *
     * @param  string $name
     * @return mixed
     */
    protected function getUnformatted($name)
    {
        return $this->data[$name];
    }


    protected function processSaveInput()
    {

        $post = api("request")->post;
        // loop through the templates available fields so that we only set values
        // for available fields and ignore the rest
        $fields = $this->template->fields;

        // add the default fields
        if (count($this->defaultFields)) {
            $fields->import($this->defaultFields);
        }


        $saveData = array();

        foreach ($fields as $field) {
            $value = isset($post->{$field->name}) ? $post->{$field->name} : $this->getUnformatted("$field->name");

            $value = $field->type->getSave($value);
            $saveData[$field->name] = $value;
        }

        $this->data = $saveData;


    }

    protected function processSaveName()
    {

        $post = api("request")->post;

        if (!$this->isNew()) {
            $previousName = $this->name;
        }


        // set name value
        if ($post->name) { // TODO : this is temp
            $pageName = api("sanitizer")->name($post->name); // TODO add page name sanitizer
            $this->name = $pageName;
        } else { // generate page name from defined field
            $this->name = $this->getName();
        }

    }


    protected function processSavePath()
    {

        // handle new object creation
        if ($this->isNew()) {
            // TODO - validate parent

            if (!$this->parent instanceof Page) {
                throw new Exception("cannot create new page without valid parent");
            }

            $this->path = $this->parent->path . $this->name . "/";

            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

    }

    protected function saveFile($path, $filename)
    {

        file_put_contents(
            $path . $filename,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );

    }

    public function save($saveName = null)
    {

//      $saveFile = self::DEFAULT_SAVE_FILE;
        $saveFile = "test.json";

        $this->processSaveInput();
        $this->processSaveName();
        $this->processSavePath();
        $this->saveFile($this->path, $saveFile);

    }

    public function rename($name)
    {

        if ($name == $this->name) {
            return false;
        }

        $current = $this->path;
        $destination = $this->parent->path . $name . "/";

        rename($current, $destination);

    }


    public function isNew()
    {
        // if the object does not have a matching existing directory it is assumed new
        // directory will be created when saved
        if (!is_file($this->_file)) {
            return true;
        }

    }


    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }


    public function settings($array = null){

        if (is_null($array)){
            $settings = objectify($this->_settings);
            return $settings;
        }
        else if(count($array)){
            $this->_settings = array_merge($this->_settings, $array);
            return $this;
        }

    }


    public function setting($key, $value = false)
    {
        if (!$value && $this->hasSetting($key)) {
            return $this->_settings[$key];
        } else {
            if ($value) {
                $this->_settings[$key] = $value;
                return $this;
            }
        }
        return false;

    }


    public function hasSetting($key)
    {
        return isset($this->_settings[$key]);
    }



    public function get($name)
    {
        switch ($name) {

            case 'directory':
                return normalizeDirectory($this->name);
            case 'url':
                return api('config')->urls->site . $this->rootFolder . "/" . $this->name . "/";
            case 'path':
            case 'name':
                return $this->{$name};
            case 'className':
                return get_class($this);
            case 'settings':
                return $this->settings(null);
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
        switch ($name) {
            case "name":
                $name = api("sanitizer")->name($value);
                $this->name = $name;
        }
        $this->data[$name] = $value;
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

    public function jsonSerialize() {
        return $this->data;
    }

}
