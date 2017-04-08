<?php

abstract class Object extends Flatbed implements JsonSerializable
{

    const DEFAULT_SAVE_FILE = "data.json";
    const DATA_FOLDER = '';

    protected $file;
    protected $name;
    protected $path;
    protected $uri;
    protected $url;

    protected $rootPath;
    protected $isSystem;

    // main data container, holds data loaded from JSON file
    protected $data = [];
    protected $data_formatted = []; // TODO : evaluate the need for this, not yet implemented?

    // prep to have a system to turn fromatting on and off TODO: use this, lol
    protected $enableFormatting = false;

    public function __construct( $file = null )
    {

        // $this->rootPath = $this->getRootPath();

        if (!is_null($file)) {

            if (!file_exists($file)) {
                throw new FlatbedException("Invalid file passed to {$this->className} class.");
            }

            $this->file = $file;
            $this->path = dirname($file) . DIRECTORY_SEPARATOR;
            $this->name = basename($this->path);
            $this->url = $this->getUrl();
            $this->uri = trim($this->url, "/");
            
            $this->data = $this->getData();

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
    protected function getData()
    {
        return json_decode(file_get_contents($this->file), true);
    }


    /**
    * @return string  path to the current object data file
    */
    public function getModified(): FlatbedDateTime
    {
        $time = filemtime($this->file);
        $datetime = new FlatbedDateTime();

        // set default format if defined in config
        if ($this->api("config")->get('dateTimeFormat')) {
            $datetime->setOutputFormat($this->api("config")->get('dateTimeFormat'));
        }

        $datetime->createFromFormat("U", $time);
        return $datetime;
    }

    /**
     * Get, instantiate and store the Objects Template
     * Return the template if already stored
     * @return Template
     */
    public function getTemplate(): Template
    {
        $template = $this->data['template'];
        return $this->api('templates')->get($template);
    }


    /**
    * @return  string  the public accessible url for this Object
    *
    */
    protected function getUrl()
    {
        $url = str_replace(ROOT_PATH, "", $this->path);
        $url = str_replace("\\", "/", $url);
        $url = "/$url";
        return $url;
    }

    /**
    * @return  string  the public accessible url for this Object
    *
    */
    protected function getUri()
    {
        $url = str_replace(ROOT_PATH, "", $this->path);
        $url = str_replace("\\", "/", $url);
        $url = trim($url, "/");
        return $url;
    }

    protected function getFormatted($name)
    {

        // set raw value, return null if no raw value available
        if (!$value = $this->getUnformatted($name)) {
            return null;
        }

        // get the field object matching the passed "$name"
        if ($this->api("fields")) {
            $field = $this->api("fields")->get($name);
        }
        // use field formatting if instance of field is available and API extensions is accesible
        // TODO: extensions should always be available
        if ($field instanceof Field) {
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
        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; 
        // TODO: why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed

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
        return $this->data[$name] ?? null;
    }


    /**
    * @return bool
    *
    * Determine if Object is a system object
    *
    */
    public function isSystem(): bool
    {

        // not system if new since new items can't be added to system
        if ($this->isNew()) {
            return false;
        }

        // check if the system path is found at the beginning of this Objects file
        return substr($this->file, 0, strlen(SYSTEM_PATH)) === SYSTEM_PATH;
    }

    /**
    * @return bool
    *
    * Used to check if the page exist in the filesystem yet
    *
    */
    public function isNew(): bool
    {
        return !file_exists($this->file);
    }


    /**
    *
    *  Placeholder, will return true for pages that can be edited
    *  for now allows editing of non system content,
    *  should check user permission in future
    *  TODO :  finish this
    *
    * @return bool
    */
    public function isEditable(): bool
    {
        return !$this->isSystem();
    }


    /**
    *
    *  Placeholder, will return true for pages that can be removed
    *  for now allows deletion of non system content,
    *  should check user permission in future
    *  TODO :  finish this
    *
    * @return bool
    */
    public function isDeletable(): bool
    {
        return !$this->isSystem();
    }


    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $name)
    {
        switch ($name) {
            // these properties are allowed public viewing, 
            // but should not be able to be directly updated
            case 'name':
            case 'uri':
            case 'path':
            case 'url':
                return $this->{$name};
            case 'urlEdit':
                // TODO: temp solution for save redirect (maybe add via a hook)
                $url = $this->api('admin')->route->url . self::DATA_FOLDER . "/edit/" . $this->uri;
                return $url;
            case 'modified':
                return $this->getModified();
            case 'className':
                return get_class($this);
            case 'defaultFields':
                return $this->defaultFields;
            case 'template':
                return $this->getTemplate();
            default:
                return $this->getFormatted($name);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set(string $name, $value)
    {
        switch ($name) {
            case 'name':
                $this->setName($value);
                break;

            default:
                $this->setFormatted($name, $value);
                break;
        }
        return $this;
    }

    /**
    * setter magic method
    * @param string $name
    * @param mixed $value
    */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }


    public function __isset(string $name)
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
