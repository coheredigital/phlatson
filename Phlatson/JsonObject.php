<?php
namespace Phlatson;

class JsonObject
{

    public string $file;
    public string $filename;
    public string $path;
    protected array $data;


    public function __construct(string $file) {

        $this->file = $file;

        if (!file_exists($file)) {
            throw new \Exception("File ($file) does not exist");
        }

        // setup some core properties
        $this->filename = basename($this->file);
        $this->path = dirname($this->file) . "/";

        $this->data = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);

        // check that we got data back from json_decode
        if ($this->data === null) {
            throw new \Exception("File ($file) is not a valid JSON file");
        }

        // populate inferred data
        $this->set('modified', \filemtime($this->file));
        // $this->set('path', dirname($this->file) . "/");

    }

    /**
     * Gets and returns a key from the data array
     *
     * @param string $key
     * @return Mixed
     */
    public function get(string $key)
    {
        switch ($key) {
            default:
                return $this->data[$key] ?? null;
                break;
        }
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
        $json = json_encode($this->data, JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);
        file_put_contents($this->file, $json);
    }

}
