<?php

abstract class Object extends Core implements Countable, IteratorAggregate
{
    const DATA_FILE = "data.json";

    public $path;
    protected $data;
    protected $root;
    protected $location = null; // whether found in site or system

    private $outputFormat = "output";

    // default static flags (mostly boolean int, all stored as int)
    protected $defaultFlags = array(
        "published" => 1,
        "locked" => 0
    );
    protected $defaultFields = array();

    protected $route = array();

    function __construct($url)
    {
        // default to using the name when no url parameter passed
        $this->route = api("input")->request;
        $this->setupData();

    }

    protected function setupData()
    {

        $folder = api('config')->paths->site . $this->root . $this->directory;
        $path = realpath($folder) . DIRECTORY_SEPARATOR;
        $file = $path . Object::DATA_FILE;

        if (is_file($file)) { // check site path first
            $this->set("path", $path);
            $this->location = "site/";
        }
        else {
            $folder = api('config')->paths->system . $this->root . $this->directory;
            $path = realpath($folder) . DIRECTORY_SEPARATOR;
            $file = $path . Object::DATA_FILE;

            if (is_file($file)) {
                $this->set("path", $path);
                $this->location = "system/";
            }
        }

        if (is_file($file)) {
            $this->data = json_decode(file_get_contents($file), true);
        }

    }

    // for now basically an XPATH alias
    public function find($name)
    {
        // TEMP TO DEAL WITH OLD XPATH USE
        return $this->data->{$name};
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

    public function save($postData = null)
    {


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

        // save to file
        if(api("config")->disableSave){
            $saveFile = "save.json";
        }
        else{
            $saveFile = self::DATA_FILE;
        }

        $saveData = json_encode($saveData);

        file_put_contents($this->path.$saveFile, $saveData);

    }

    public function get($string)
    {
        switch ($string) {
            case 'name':
                $lastRequestIndex = count($this->route) - 1;
                return $this->route[$lastRequestIndex];
            case 'url':
                return $this->api('config')->urls->root . $this->location . $this->root . $this->name . "/";
                break;
            case 'requests':
                return $this->route;
                break;
            case 'directory':
                $directory = trim(implode("/", $this->route), "/");
                return $directory;
            case 'template':
                $templateName = $this->getUnformatted("template");
                $template = new Template($templateName);
                return $template;
            case 'class':
            case 'className':
                return $this->className();
            default:
                return $this->getFormatted($string, $this->outputFormat);
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
            default:
                $value = is_object($value) ? (string)"$value" : $value;
                $this->data[$name] = $value;
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
