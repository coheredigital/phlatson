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
    protected $template;

    protected $rootPath;
    protected $isSystem;

    // main data container, holds data loaded from JSON file
    protected $data = [];
    protected $settings;
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

            $this->template = $this->getTemplate();

            $this->data('modified', filemtime($this->file));

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
     * Get, instantiate and store the Objects Template
     * Return the template if already stored
     * @return Template
     */
    public function setTemplate( string $name) : self
    {
        
        if(!$template = $this->data('template')) {
            return null;
        }

        return $this->api('templates')->get($template);
    }

    /**
     * Get, instantiate and store the Objects Template
     * Return the template if already stored
     * @return Template
     */
    public function getTemplate(?string $name = null): ?Template
    {
        $name = $name !== null ? $name : $this->data('template');

        if($name === null) return null;

        return $this->api('templates')->get($name);
    }

    /**
     * get the named setting
     * @param  string $name
     * @return mixed
     */
    public function setting($name)
    {   
        $settings = $this->data('settings');
        return $settings[$name] ?? null;
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

    protected function getField($name) {

        if (!$this->get('template') instanceof Template) {
            return null;
        }

        // $found = $this->template->hasField($name);

        // if ($this->template->hasField($name)) {
            return $this->api("fields")->get($name);
        // }
        
        return null;

    }


    protected function getFormatted($name)
    {

        // return null if no raw value available
        if (!$this->has($name)) {
            return null;
        }

        // set raw value
        $value = $this->data($name);

        // get the field object matching the passed "$name"
        $field = $this->getField($name);        

        // // use field formatting if instance of field is available and API extensions is accesible
        if ($field instanceof Field && $fieldtype = $field->get('fieldtype')) {
            $value = $fieldtype->output($value);
        }

        return $value;
    }


    protected function setFormatted($name, $value)
    {
        
        // get the field object matching the passed "$name"
        if ($this->api("fields")) {
            $field = $this->getField($name); 
        }

        if ($field instanceof Field && $fieldtype = $field->get('fieldtype')) {
            $value = $fieldtype->input($value);
        }
        // store raw value
        $this->data($name, $value);
        
    }


    /**
    *
    * skips formatting of passed value
    * get / set value directly from / to $this->data[$name]
    * 
    *
    * @param  string $name
    * @return mixed
    */
    protected function data( string $name, $value = null)
    {
        if ($value === null) {
            return $this->data[$name] ?? null;
        }
        
        $this->data[$name] = $value;
        return $this;
        
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
            case 'template':
                return $this->{$name};
            case 'modified':
                return $this->getModified();
            case 'className':
                return get_class($this);
            case 'defaultFields':
                return $this->defaultFields;
            // case 'template':
            //     return $this->getTemplate();
            default:
                return $this->getFormatted($name);
        }
    }

    public function set(string $name, $value)
    {
        switch ($name) {
            case 'name':
                $this->name = $value;
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
    /**
     * give property access to all get() variables
     * @param  string $name
     * @return mixed
     */
    final public function __get( string $name)
    {
        return $this->get($name);
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
