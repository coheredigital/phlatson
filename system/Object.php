<?php

abstract class Object implements JsonSerializable
{

    use hookable;


    const DEFAULT_SAVE_FILE = "data.json";

    public $name;

    protected $path;
    protected $file;
    protected $modified;
    protected $rootFolder;

    // main data container, holds data loaded from JSON file
    protected $data = array();
    protected $previousData = array();
    protected $settings = array();

    protected $defaultFields = array();
    protected $route = array();

    function __construct($file = null)
    {

        if (is_file($file)) {
            $this->file = $file;
            $this->path = $this->getPath();
            $this->route = $this->getRoute();
            $this->data = json_decode(file_get_contents($this->file), true);
            $this->name = $this->getName();
            $this->modified = filemtime($this->file);
        }

    }


    protected function getName()
    {
        return basename($this->path);
    }


    protected function getPath()
    {
        return normalizePath(str_replace(Object::DEFAULT_SAVE_FILE, "", $this->file));
    }


    protected function getRoute()
    {

        // first get roo relative path
        $path = str_replace(
            app("config")->paths->site . $this->rootFolder,
            "",
            $this->file
        ); // trim the root path to get root relative path
        $path = str_replace($this::DEFAULT_SAVE_FILE, "", $path); // trim of file name to isolote path
        $path = rtrim($path, '/'); // trim excess slashes

        // break the path into it's parts an return the resulting array
        return explode("/", $path);
    }

    protected function getFormatted($name)
    {

        // get raw value
        $value = $this->getUnformatted($name);

        // get the field object matching the passed "$name"

        $field = app("fields") ? app("fields")->get($name) : false; // TODO, why am I check if the app("fields") instance exist yet, this shouldn't be needed, if it is I should note the reason here

        if ($field instanceof Field ) {
            $fieldtype = $field->type;
            $fieldtype->object = $this;
            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getOutput($value);
            }
        }


        return $value;
    }


    protected function setFormatted($name, $value)
    {

        // get the field object matching the passed "$name"

        $field = app("fields") ? app("fields")->get($name) : false; // TODO, why am I check if the app("fields") instance exist yet, this shouldn't be needed

        if ($field instanceof Field ) {
            $fieldtype = $field->type;
            $fieldtype->object = $this;

            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getSave($value);
            }
        }

        $this->setUnformatted($name, $value);
    }


    protected function getDefaultFields(){

        $fieldArray = new ObjectArray();

        foreach($this->defaultFields as $fieldName){
            $field = app("fields")->get($fieldName);
            if($field instanceof Field) $fieldArray->add($field);
        }
        return $fieldArray;
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
     * should not generally be used on public facing API
     *
     * @param  string $name
     * @return mixed
     */
    protected function getUnformatted($name)
    {
        $value = $this->data[$name];
        return $value;
    }


    protected function processInputData()
    {

        $post = app("request")->post;

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

    protected function processSaveName()
    {

        $post = app("request")->post;

        // set name value
        if ($post->name && !$this->isNew()) { // TODO : this is temp
            $pageName = app("sanitizer")->name($post->name); // TODO add page name sanitizer
            $this->name = $pageName;
        } else { // generate page name from defined field
            $template = $this->template;
            $nameFieldReference = $template->settings['nameFrom'];
            $name = $this->get($nameFieldReference);
            $name = app("sanitizer")->name($name);
            $this->name = $name;// TODO:  refactor code to allow name setting and getting to be handled the same way as other fields (by the Fieldtype class associated with it)
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

        // store objects existing data for reference
        $this->previousData = $this->data;

        if(app("config")->simulate){
            $saveFile = "test.json";
        }
        else{
            $saveFile = self::DEFAULT_SAVE_FILE;
        }

        $this->processSaveName();
        $this->processSavePath();
        $this->saveFile($this->path, $saveFile);

    }

    public function _rename($name)
    {

        if ($name == $this->name) {
            return false;
        }

        $current = $this->path;
        $destination = $this->parent->path . $name . "/";

        rename($current, $destination);

    }


    public function _delete()
    {

        // recursively remove all child file and folders before removing self
        $it = new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($this->path);
    }


    public function isNew()
    {
        // if the object does not have a matching existing directory it is assumed new
        // directory will be created when saved
        if (!is_file($this->file)) {
            return true;
        }

    }


    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }


//    public function settings($array = null){
//
//        if (is_null($array)){
//            $settings = objectify($this->_settings);
//            return $settings;
//        }
//        else if(count($array)){
//            $this->_settings = array_merge($this->_settings, $array);
//            return $this;
//        }
//
//    }
//
//
//    public function setting($key, $value = false)
//    {
//        if (!$value && $this->hasSetting($key)) {
//            return $this->_settings[$key];
//        } else {
//            if ($value) {
//                $this->_settings[$key] = $value;
//                return $this;
//            }
//        }
//        return false;
//
//    }
//
//
//    public function hasSetting($key)
//    {
//        return isset($this->_settings[$key]);
//    }



    public function get($name)
    {
        switch ($name) {

            case 'directory':
                return normalizeDirectory($this->name);
            case 'url':
                return app('config')->urls->site . $this->rootFolder . "/" . $this->name . "/";
            case 'path':
            case 'name':
                return $this->{$name};
            case 'className':
                return get_class($this);
//            case 'settings':
//                return $this->settings(null);
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
                $name = app("sanitizer")->name($value);
                $this->name = $name;
        }
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

    public function jsonSerialize() {
        return $this->data;
    }

}
