<?php

abstract class Object extends Flatbed implements JsonSerializable
{

    use hookable;

    const DEFAULT_SAVE_FILE = "data.json";
    const DATA_FOLDER = '';

    protected $file;
    protected $name;

    protected $rootPath;

    protected $isSystem;

    // main data container, holds data loaded from JSON file
    protected $data = [];

    // prep to have a system to turn fromatting on and off TODO: use this, lol
    protected $enableFormatting = false;


    public function __construct( string $file = '')
    {

        $this->rootPath = $this->getRootPath();

        if ($file) {

            if (!file_exists($file)) {
                throw new FlatbedException("Invalid file passed to {$this->className} class.");
            }

            $this->file = $file;
            $this->name = $this->getName();
            $this->data = $this->getData();

        } else {
            $this->rootPath = SITE_PATH . self::DATA_FOLDER;
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


    public function getName()
    {
        return basename($this->getPath());
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string full system path (ie: C:/root/site/etc/)
     *
     * Determine rootPAth of this Object type from the Objects $file
     *
     */
    public function getRootPath(): string
    {
        if ($this->isNew()) {
            $path = SITE_PATH . self::DATA_FOLDER;
        }
        else {
            $root = $this->isSystem() ? SYSTEM_PATH : SITE_PATH;
            $path = $root . self::DATA_FOLDER;
        }

        return $path;
    }




    /**
     * @return string
     *
     * Directory refers to the rootPath relative folder
     *
     */
    public function getDirectory(): string
    {

        $path = str_replace($this->getRootPath(), "", $this->getPath() );
        $path =  Filter::uri($path);
        return $path;
    }


    /**
     * @return string  path to the current object data file
     */
    public function getPath(): string
    {

        $path = '';
        if (is_file($this->file)) {
            $path = dirname($this->file);
        } else {
            $path = $this->rootPath . $this->name;
        }

        // return Filter::path($path);
        return $path;
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
     * @return  string  the public accessible url for this Object
     *
     */
    protected function getUrl()
    {

        // get the site root
        $rootPath = ROOT_PATH;

        // remove the ROOT_PATH, site root, and data.json from the object path to get a relative directory
        $replace = [
            ROOT_PATH,
            $this->api("config")->paths->root,
            "data.json"
        ];

        $url = str_replace($replace, "", $this->getPath());

        $url = trim($url, "/");
        return Filter::url($url);
    }

    protected function getFormatted($name)
    {

        // get raw value

        if (!$value = $this->getUnformatted($name)) {
            return null;
        }

        // get the field object matching the passed "$name"
        if ($this->api("fields")) {
            $field = $this->api("fields")->get($name);
        }
        // use field formatting if instance of field is available and API extensions is accesible
        // TODO: extensions should always be available
        if ($field instanceof Field && $this->api("extensions")) {

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
        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed

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
        return isset($this->data[$name]) ? $this->data[$name] : null;
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


    // protected function checkDataIntegrity()
    // {
    //
    //     foreach ($this->requiredElements as $name) {
    //
    //         if (!$this->has($name)) {
    //             throw new FlatbedException(" Cannot continue: missing '$name' in $this '$this->name' ($this->file) from required elements (" . implode(", ",
    //                     $this->requiredElements) . ").");
    //         }
    //
    //     }
    //
    // }


    public function get(string $name)
    {
        switch ($name) {
            case 'name':
                return $this->getName();
            case 'uri':
            case 'directory':
                return $this->getDirectory();
            case 'url':
                return $this->getUrl();
            case 'path':
                return $this->getPath();
            case 'urlEdit':
                // TODO: temp solution for save redirect (maybe add via a hook)
                $url = $this->api('admin')->route->url . self::DATA_FOLDER . "/edit/" . $this->getDirectory();
                return $url;
            case 'modified':
                return $this->getModified();
            case 'className':
                return get_class($this);
            case 'defaultFields':
                return $this->defaultFields;
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
