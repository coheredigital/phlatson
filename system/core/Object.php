<?php

abstract class Object
{

    private $className = null;

    const DATA_FILE = "data.json";
    protected $rootFolder;
    protected $objectOrigin = null;

    protected $path;
    protected $file;

    protected $data = array();

    protected $defaultFields = array();
    protected $route = array();

    function __construct($file = null)
    {
        $this->file = $file;


        $this->route = $this->getRoute($file);
        $this->loadData($file);
        $this->setName();
    }

    protected function loadData($file)
    {
        $this->file = $file;
        $this->path = normalizePath(str_replace(Object::DATA_FILE,"",$file));
        $this->data = json_decode(file_get_contents($file), true);
    }

    protected function setName()
    {
        // set object name
        $lastRequestIndex = count($this->route) - 1;
        $this->name = $this->route[$lastRequestIndex];
    }

    protected function getRoute($file)
    {
        $relativePath = str_replace(api::get("config")->paths->root, "", $file );
        $relativePath = str_replace($this::DATA_FILE, "", $relativePath );
        $relativePath = rtrim($relativePath, '/');

        if (strpos($relativePath, "/") === false) throw new Exception("Invalid request - {$file} - passed to {$this->className}");

        $array = explode("/", $relativePath);

        $this->objectOrigin = array_shift($array);
        $rootFolder = array_shift($array);

        if($rootFolder !== $this->rootFolder && $rootFolder !== $this->className ) throw new Exception("Invalid request passed to {$this->className} : array( " . implode(", ", $array) . " ) $rootFolder !== $this->rootFolder || $rootFolder !== $this->className");

        if(!count($array)) $array[] = "";

        return $array;
    }

    protected function getFormatted($name)
    {

        // get raw value
        $value = $this->getUnformatted($name);

        // get the field object matching the passed "$name"
        $field = api::get("fields")->get($name);
        if ( $field ){
            $fieldtype = $field->type;
            if ( $this instanceof Template && $name === "fields") { // TODO : special case could be handled better
                $fieldtype->setObject($this->referenceObject);
            }
            else{
                $fieldtype->setObject($this);
            }

            $value = $fieldtype->getOutput($value);
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
    public function getUnformatted($name)
    {
        return $this->data[$name];
    }

    public function save($postData = null, $saveName = null)
    {

        // loop through the templates available fields so that we only set values
        // for available fields and ignore the rest
        $fields = $this->template->fields;

        // add the default fields
        if(count($this->defaultFields)) $fields->import($this->defaultFields);

        // setup array to store save data
        $saveData = array();

        foreach ($fields as $field) {

            $value = isset($postData->{$field->name}) ? $postData->{$field->name} : $this->getUnformatted("$field->name");

            $fieldtype = $field->type();
            $value = $fieldtype->getSave($value);

            $saveData[$field->name] = $value;
        }

        // set name value
        if($postData->name){ // TODO : this is temp
            $pageName = $postData->name; // TODO add page name sanitizer
            $this->name = $pageName;
        }
        else{ // generate page name from defined field
            $this->name = api::get("sanitizer")->name($postData->title);
        }

        // handle new object creation
        if($this->isNew()){
            // TODO - validate parent

            if(!$this->parent instanceof Page){
                throw new Exception("cannot create new page without valid parent");
            }

            $this->path = $this->parent->path . $this->name . "/";
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }


        // save to file
        if($saveName){
            $saveFile = "$saveName.json";
        }
        else{
            $saveFile = self::DATA_FILE;
        }

        $saveData = json_encode($saveData, JSON_PRETTY_PRINT);

        file_put_contents( $this->path . $saveFile , $saveData );

    }


    public function isNew(){
        // if the object does not have a matching existing directory it is assumed new
        // directory will be created when saved
        if(is_null($this->file)) return true;

    }


    public function has($key){
        return array_key_exists($key,$this->data);
    }


    public function get($name)
    {
        switch ($name) {
            case 'directory':
                return normalizeDirectory($this->name);
            case 'location':
                // we assume site location if system path not found because new Object can only be added to site and not system
                if( strpos($this->file, api::get("config")->paths->system) !== false){
                    return "system";
                }
                else{
                    return "site";
                }
            case 'url':
                return api::get('config')->urls->root . $this->location . "/" . $this->rootFolder . "/" . $this->name . "/";
            case 'path':
                return $this->path;
            case 'requests':
                return $this->route;
            case 'class':
            case 'className':
                return $this->className();
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
        $this->data[$name] = $value;
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }




    public function className()
    {

        if (!isset($this->className)) {
            $this->className = get_class($this);
        }
        return $this->className;

    }

    public function __toString()
    {
        return $this->className();
    }


}
