<?php

abstract class Object extends Core implements Countable, IteratorAggregate
{

    const DATA_FILE = "data.json";

    // private $name;
    public $path;
    protected $data;
    // default statuc flags (mostly boolean int, all stored as int)
    protected $defaultFlags = array(
        "published" => 1,
        "locked" => 0
    );
    protected $defaultFields = array();
    protected $flags = array();

    protected $dataFolder;
    // protected $dataFile = "data.js"; // what file name should be checked for data
    protected $location = null; // whether found in site or system
    private $outputFormat = "output";

    public $urlRequest = array();

    function __construct($url = null)
    {
        $this->data = new stdClass();
        $url = $url ? $url : $this->name;
        $this->urlRequest = $this->getUrlRequest($url);
        try {
            $this->setupData();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }


    protected function setupData($path = null)
    {

        if (is_null($path)) {
            $sitePath = realpath(
                    $this->api('config')->paths->site . $this->dataFolder . $this->directory
                ) . DIRECTORY_SEPARATOR;
            $systemPath = realpath(
                    $this->api('config')->paths->system . $this->dataFolder . $this->directory
                ) . DIRECTORY_SEPARATOR;

            if (is_file($sitePath . Object::DATA_FILE)) {
                $this->path = $sitePath;
                $this->location = "site/";
            } else {
                if (is_file($systemPath . Object::DATA_FILE)) {
                    $this->path = $systemPath;
                    $this->location = "system/";
                }
            }
        } else {

            $path = realpath($path) . DIRECTORY_SEPARATOR;

            if (is_file($path . Object::DATA_FILE)) {
                $this->path = $path;
            }

        }


        $this->data = $this->getData();
        $this->setFlags();

    }

    /* =====================
     Status / Flag functions
    ====================== */

    protected function setFlags()
    {
        if (!$data->flags) {
            return;
        }

        // first merge in default flags
        $this->flags = array_merge($this->flags, $this->defaultFlags);


        foreach ($this->data->flags->children() as $key => $value) {
            $this->flags["$key"] = (int)$value;
        }
        unset($this->data->flags);
    }


    /* MAGIC!! */
    public function __get($name)
    {
        // handle / cache class name request
        if ($name == "className") {
            return $this->className();
        }
        return $this->get($name);
    }

    public function get($string)
    {
        switch ($string) {
            case 'name':
                $lastRequestIndex = count($this->urlRequest) - 1;
                return (string)$this->urlRequest[$lastRequestIndex];
            case 'url':
                return $this->url();
                break;
            case 'requests':
                return $this->urlRequest;
                break;
            case 'directory':
                $directory = trim(implode("/", $this->urlRequest), "/");
                return $directory;
            default:
                return $this->getFormatted($string, $this->outputFormat);
                break;
        }
    }

    // for now basically an XPATH alias
    public function find($name)
    {
        // TEMP TO DEAL WITH OLD XPATH USE
        return $this->data->{$name};
    }


    public function url()
    {
        return $this->api('config')->urls->root . $this->location . $this->dataFolder . $this->name . "/";
    }


    /**
     * Load XML file into data object for access and reference
     */
    protected function getData()
    {
        $file = $this->path . Object::DATA_FILE;
        if (is_file($file)) {

            return json_decode(file_get_contents($file), true);
        }
    }


    protected function createUrl($array)
    {
        if (is_array($array) && implode("", $this->urlRequest)) {
            $url = "/" . implode("/", $array);
            return $url;
        }
        return null;
    }


    protected function getUrlRequest($url)
    {
        $url = rtrim((string)$url, '/');
        $array = array();
        if (strpos($url, "/") !== false) {
            $array = explode("/", $url);
        } else {
            $array[] = $url;
        }
        return $array;
    }


    public function getTemplate($name = null)
    {
        // $templateName = $this->data->template;
        $templateName = $name ? $name : $this->data->template;
        if ($templateName) {
            $template = new Template($templateName);
        }
        return $template;
    }


    protected function getFormatted($name, $type)
    {
        // return null if data does not exist
//        if (!$this->data || !$this->data->{$name}) {
        if (!$this->data || !$this->data[$name]) {
            return null;
        }

        // get raw value
        // $value = $this->data->{$name};
        $value = $this->data[$name];

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
     * sets the outputFormat to be used for get method
     * @param  string $name should match one of valid format optional available (output, edit, raw, save)
     */
    public function setFormat($name)
    {
        $this->outputFormat = $name;
    }


    /**
     * public alias for getFormatted($name, "raw")
     * @param  string $name
     * @return mixed
     */
    public function getUnformatted($name)
    {
        return $this->getFormatted($name, "raw");
    }


    public function save($postData = null)
    {


        // loop through the templates available fields so that we only set values
        // for available feilds and ignore the rest
        $fields = $this->template->fields($this->defaultFields);


        foreach ($fields as $field) {

            $value = $postData->{$field->name};

            $fieldtype = $field->type();

            $formattedValue = $value ? $fieldtype->getSave($field->name, $value) : $this->{$field->name};
            
            $this->set($field->name,$formattedValue);
            
        }

        // save to file
        if(api("config")->debug){
            $saveFile = "save.json";
        }
        else{
            $saveFile = self::DATA_FILE;
        }

        file_put_contents($this->path.$saveFile, json_encode($this->data));

    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    public function set($name, $value)
    {
        $value = is_object($value) ? (string)"$value" : $value;
//        $this->data->{$name} = $value;
        $this->data[$name] = $value;
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
