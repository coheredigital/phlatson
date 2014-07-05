<?php

abstract class Object extends Core
{
    const DATA_FILE = "data.json";
    protected $rootFolder;
    protected $objectOrigin = null;

    protected $path;
    protected $file;

    protected $data = array();

    protected $defaultFields = array();
    protected $route = array();

    function __construct($file = null, $directory = null)
    {
        $this->route = $this->getRoute($file);
        $this->load($file);
    }

    protected function load($file)
    {

        // check site path first
        if (!is_file($file)) return false;

        $this->file = $file;
        $this->path = normalizePath(str_replace(Object::DATA_FILE,"",$file));
        $this->data = json_decode(file_get_contents($file), true);

    }

    protected function getRoute($file)
    {
        $relativePath = str_replace(api("config")->paths->root, "", $file );
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
        $field = api("fields")->get($name);
        if ( $field ){
            $fieldtype = $field->type();
            $value = $fieldtype->get($value, "output");
        }

        return $value;
    }

    /**
     * public alias for getFormatted($name, "raw")
     * @param  string $name
     * @return mixed
     */
    public function getUnformatted($name)
    {
        return $this->data[$name];
    }

    public function save($postData = null, $saveName = null)
    {

        // TODO - add more validation to page save


        // loop through the templates available fields so that we only set values
        // for available feilds and ignore the rest
        $template = $this->get("template");
        $fields = $template->fields;

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
        if($postData->name){
            $pageName = $postData->name; // TODO add page name sanitizer
            $this->name = $pageName;
        }
        else{ // generate page name from defined field
            $this->name = api("sanitizer")->name($postData->title);
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
//            $saveFile = self::DATA_FILE;
            $saveFile = "test.json";
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
            case 'name':
                $lastRequestIndex = count($this->route) - 1;
                $name = $this->route[$lastRequestIndex];
                return $name;
            case 'directory':
                return normalizeDirectory($this->name);
            case 'location':
                // we assume site location if system path not found because new Object can only be added to site and not system
                if( strpos($this->file, api("config")->paths->system) !== false){
                    return "system";
                }
                else{
                    return "site";
                }
            case 'url':
                return $this->api('config')->urls->root . $this->location . "/" . $this->rootFolder . "/" . $this->name . "/";
            case 'path':
                return $this->path;
            case 'requests':
                return $this->route;
            case 'template':
                $template = $this->getUnformatted("template");
                $template = api("templates")->get($template);
                return $template;
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
        switch($name){
            // TODO: reevaluate, not sure I like the way the name is set as appossed to the object, I believe I did this for saving reasons
            case 'template':
                if($value instanceof Template){
                    $this->data["template"] = $value->name;
                    break;
                }
            default:
                $this->data[$name] = $value;
        }
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }


}
