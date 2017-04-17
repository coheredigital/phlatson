<?php
class Extension extends Object implements ObjectInterface
{

    protected $rootFolder = "extensions";
    protected $requiredElements = ['title', 'type'];

    final public function __construct($file = null)
    {
        $file = $this->getFile();
        parent::__construct($file);

        if (method_exists($this, 'setup')) {
            $this->setup();
        }

    }

    protected function getFile()
    {
        $reflection = new ReflectionClass($this);
        $directory = dirname($reflection->getFileName()) . DIRECTORY_SEPARATOR;
        $file = $directory . "data.json";
        return $file;
    }

    /**
     * @return boolean
     *
     * Check if extension has configuration settings
     */
    public function isConfigurable(): boolean
    {
        return file_exists("{$this->path}defaultConfiguration.json");
    }

}
