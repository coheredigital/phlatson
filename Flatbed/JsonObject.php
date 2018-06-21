<?php
namespace Flatbed;

class JsonObject
{

    protected $filepath;
    protected $data;

    public function __construct(string $filepath ) {
        
        $this->filepath = ROOT_PATH . $filepath;

        if (!file_exists($this->filepath)) {
            throw new Exceptions\FlatbedException("File ($this->filepath) deos not exist");
        }

        $this->data = json_decode(file_get_contents($this->filepath), true);

        // check that we got data back from json_decode
        if ($this->data === null) {
            throw new Exceptions\FlatbedException("File ($this->filepath) is not a valid JSON file");
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
        file_put_contents($this->filepath, $json);
    }

}
