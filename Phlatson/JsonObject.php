<?php
namespace Phlatson;

class JsonObject
{

    public $file;
    public $filename;
    public $path;

    protected $data;

    public function __construct(string $file ) {
        
        $this->file = rtrim(ROOT_PATH, "/")  . $file;
        

        if (!file_exists($this->file)) {
            throw new Exceptions\PhlatsonException("File ($this->file) deos not exist");
        }

        // setup some core properties
        $this->filename = basename($this->file);
        $this->path = dirname($this->file);

        $this->data = json_decode(file_get_contents($this->file), true);

        // check that we got data back from json_decode
        if ($this->data === null) {
            throw new Exceptions\PhlatsonException("File ($this->file) is not a valid JSON file");
        }

    }

    /**
     * Gets and returns a key from the data array
     *
     * @param string $key
     * @return Mixed
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * Get the time the file was last modified
     */
    public function getModifiedTime() : int
    {
        return \filemtime($this->file);
    }

    /**
     * Set the $key in $data array to the supplied $value
     *
     * @param String $key
     * @param Mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function save()
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($this->file, $json);
    }

}