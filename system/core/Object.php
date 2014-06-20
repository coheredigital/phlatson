<?php

abstract class Object extends Core implements Countable, IteratorAggregate
{
    const DATA_FILE = "data.json";

    protected $path;
    protected $data = array();
    protected $properties = array(
        "system" => true
    );
    protected $rootFolder;


    protected $location = null; // whether found in site or system

    private $outputFormat = "output";


    protected $defaultFields = array();
    protected $route = array();

    function __construct($directory, $file = null)
    {
        // TODO : add support for proper root request without assuming a blank request is a root request, possibly turn requests into object where each level is retrieval and the original string etc.
        // default to using the name when no url parameter passed
        if(!is_null($directory)) {
            $this->route = $this->getRoute($directory);
            $this->load($file);
        }


    }

    protected function load($file = null)
    {

        // check site path first



        if (is_file($file)) {
            $this->location = "site/";
            $this->defaultFlags["system"] = 0;
        }
        else {
            //  otherwise check for file in system
            $folder = api('config')->paths->system . $this->rootFolder . $this->directory;
            $path = realpath($folder) . DIRECTORY_SEPARATOR;
            $file = $path . Object::DATA_FILE;

            if (is_file($file)) {
                $this->location = "system/";
            }
        }

        // setup data if we found a valid file (object)
        if (is_file($file)) {
            $this->path = realpath(str_replace(Object::DATA_FILE,"",$file));
            $this->data = json_decode(file_get_contents($file), true);
        }

    }

    protected function getRoute($url)
    {
        $url = rtrim((string)$url, '/');
        $array = array();
        if (strpos($url, "/") !== false) {
            $array = explode("/", $url);
        } else {
            $array[0] = $url;
        }
        return $array;
    }

    protected function getFormatted($name, $type)
    {
        // return null if data does not exist
        if (!$this->data || !$this->data[$name]) {
            return null;
        }

        // get raw value
        $value = $this->data[$name];
        if($type == "raw") return $value;


        if ($value) {
            // get the field object matching the passed "$name"
            $field = $this->api("fields")->get("$name");
            if (is_object($field)) {
                $fieldtype = $field->type;
            }

            if (is_object($fieldtype)) {
                $value = $fieldtype->get($value, $type);
            }

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
        $fields = $template->getFields();

        // add the default fields
        if(count($this->defaultFields)) $fields->import($this->defaultFields);

        // setup array to store save data
        $saveData = array();

        foreach ($fields as $field) {

            $value = isset($postData->{$field->name}) ? $postData->{$field->name} : $this->getUnformatted("$field->name");

            $fieldtype = $field->type();
            $value = $fieldtype->get($value, "save");

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

        if($this->isNew()){
            // TODO - validate parent
            $this->path = $this->parent->path . $this->name . DIRECTORY_SEPARATOR;
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
        if(!$this->directory) return true;

    }


    public function has($key){
        return array_key_exists($key,$this->data);
    }


    public function get($name)
    {
        switch ($name) {
            case 'name':
                if(is_null($this->name)){
                    $lastRequestIndex = count($this->route) - 1;
                    $this->name = $this->route[$lastRequestIndex];
                }
                return $this->name;
            case 'directory':
                return normalizeDirectory($this->name);
            case 'url':
                return $this->api('config')->urls->root . $this->location . $this->rootFolder . $this->name . "/";
                break;
            case 'path':
                return $this->path;
                break;
            case 'requests':
                return $this->route;
                break;
            case 'template':
                $templateName = $this->getUnformatted("template");
                $template = api("templates")->get($templateName);
                $template->defaultFields = $this->defaultFields;
                return $template;
            case 'class':
            case 'className':
                return $this->className();
            default:
                return $this->getFormatted($name, $this->outputFormat);
                break;
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        switch($name){
            case 'outputFormat':
                $this->outputFormat = $value; // move this into a method to handle validating the set value against allowed options
            case 'template':
                if($value instanceof Template){
                    $this->data["template"] = $value->name;
                    break;
                }
            default:
                $this->data[$name] = $value;
                // temp solution
//                $this->{$name} = $value;
        }
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    // allows the data array to be counted directly
    public function count()
    {
        return count($this->data);
    }

    // iterate the object data in a foreach
    public function getIterator()
    {
        return $this->data;
    }

}
