<?php

abstract class Object
{

    const DATA_FILE = "data.json";

    protected $name;
    protected $path;
    protected $file;
    protected $location;
    protected $modified;

    protected $rootFolder;

    // main data container, holds data loaded from JSON file
    protected $data = array();

    protected $defaultFields = array();
    protected $route = array();

    function __construct($file = null)
    {


        if ( is_file($file) ) {

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
        // set object name
        if ( $this->isNew() ){
            return $this->getNewName();
        }

        $lastRequestIndex = count($this->route) - 1;
        return $this->route[$lastRequestIndex];

    }


    protected function getPath()
    {
        if ( !$this->isNew() ){
            return normalizePath(str_replace(Object::DATA_FILE,"",$this->file));
        }
        else {

        }
    }


    protected function getRootRelativePath()
    {

        $relativePath = str_replace(api::get("config")->paths->root, "", $this->file ); // trim the root path to get root relative path
        $relativePath = str_replace($this::DATA_FILE, "", $relativePath ); // trim of file name to isolote path
        $relativePath = rtrim($relativePath, '/'); // trim excess slashes

        if (strpos($relativePath, "/") === false) throw new Exception("Invalid request - {$file} - passed to {$this->className}");
        return $relativePath;
    }



    protected function getRoute()
    {

        $pathArray = explode( "/", $this->getRootRelativePath() );

        $this->location = array_shift($pathArray);
        $rootFolder = array_shift($pathArray);

        if(
            $rootFolder !== $this->rootFolder &&
            $rootFolder !== $this->className
        ) {
            throw new Exception("Invalid request passed to {$this->className} : array( " . implode(", ", $pathArray) . " ) $rootFolder !== $this->rootFolder || $rootFolder !== $this->className");
        }

        if( !count( $pathArray ) ) {
            $pathArray[] = "";
        }

        return $pathArray;
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
    protected function getUnformatted($name)
    {
        return $this->data[$name];
    }


    protected function processSaveInput(){

        $post = api::get("input")->post;
        // loop through the templates available fields so that we only set values
        // for available fields and ignore the rest
        $fields = $this->template->fields;

        // add the default fields
        if(count($this->defaultFields)) $fields->import($this->defaultFields);


        $saveData = array();

        foreach ($fields as $field) {
            $value = isset($post->{$field->name}) ? $post->{$field->name} : $this->getUnformatted("$field->name");

            $value = $field->type->getSave($value);
            $saveData[$field->name] = $value;
        }

        $this->data = $saveData;



    }

    protected function processSaveName(){

        $post = api::get("input")->post;

        if ( !$this->isNew() ){
            $previousName = $this->name;
        }



        // set name value
        if($post->name){ // TODO : this is temp
            $pageName = api::get("sanitizer")->name($post->name); // TODO add page name sanitizer
            $this->name = $pageName;
        }
        else{ // generate page name from defined field
            $this->name = $this->getName();
        }

    }


    protected function processSavePath(){

        // handle new object creation
        if( $this->isNew() ){
            // TODO - validate parent

            if(!$this->parent instanceof Page){
                throw new Exception("cannot create new page without valid parent");
            }

            $this->path = $this->parent->path . $this->name . "/";

            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

    }

    public function save( $saveName = null )
    {

        $this->processSaveInput();
        $this->processSaveName();
        $this->processSavePath();

        // save to file
        if($saveName){
            $saveFile = "$saveName.json";
        }
        else{
//            $saveFile = self::DATA_FILE;
            $saveFile = "test.json";
        }



        file_put_contents(
            $this->path . $saveFile ,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );

    }

    public function rename($name) {

        if ( $name == $this->name ) return false;

        $current = $this->path;
        $destination = $this->parent->path . $name . "/";

        rename( $current , $destination );

    }


    public function isNew(){
        // if the object does not have a matching existing directory it is assumed new
        // directory will be created when saved
        if( !is_file($this->file) ) return true;

    }


    public function has($key){
        return array_key_exists( $key, $this->data );
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
            case 'name':
                return $this->{$name};
            case 'className':
                return get_class($this);
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
        switch ( $name ) {
            case "name":
                $name = api::get("sanitizer")->name($value);
                $this->name = $name;
        }
        $this->data[$name] = $value;
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }


    public function __toString()
    {
        return $this->className;
    }

}
