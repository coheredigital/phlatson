<?php 

namespace Phlatson;

class Finder
{
    protected $root;

    public function __construct(string $path)
    {
        $this->setRoot($path);
    }

    protected function setRoot(string $path) {
        // normalize the path
        $path = realpath($path) . DIRECTORY_SEPARATOR;
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);

        if (!file_exists($path)) {
            throw new Exceptions\PhlatsonException("Path ($path) does not exist, cannot be used as site data");
        }

        $this->root = $path;
    }


    public function getFiles(string $folder) : array
    {
        $path = $this->getPath($folder);
        $files = glob("{$path}*.json");
        return $files;
    }

    public function exists(string $folder)
    {
        $path = $this->getPath($folder);
        $file = "{$path}data.json";

        if (!file_exists($file)) {
            throw new Exceptions\PhlatsonException("File ($file) does not exist");
        }

        return $file;
    }

    public function get(string $path) : ?JsonObject
    {

        if (!$file = $this->exists($path)) {
            return null;
        }
        
        $jsonObject = new JsonObject($file);
        return $jsonObject;
	}
    
    
    public function getTypeData(string $classname, string $path) : JsonObject
    {
        // get data object
        $folder = strtolower($classname);
        // trim just in case and pluralize
        $folder = "/" . trim($folder, "/") . "s";
        
        $path = $this->sanitizeFolder($path);
        $path = "{$folder}{$path}";
        $jsonObject = $this->get($path);
        return $jsonObject;
    }

    public function getType(string $classname, $path) : ?DataObject
    {
        // get data object
        $folder = strtolower($classname);
        // trim just in case and pluralize
        $folder = "/" . trim($folder, "/") . "s";
        
        $path = $this->sanitizeFolder($path);
        $path = "{$folder}{$path}";
        $jsonObject = $this->get($path);
        
        $classname = ucfirst($classname);
        $classname = "\Phlatson\\$classname";
        
        $objectType = new $classname();
        $objectType->setData($jsonObject);
        
        return $objectType;
    }

	protected function sanitizeFolder(string $folder)
	{
		$folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);
		$folder = trim($folder, "/");
		return "/{$folder}/";
	}
	
	public function getPath(string $folder)
	{
		$folder = $this->sanitizeFolder($folder);
		return $this->root . ltrim($folder,"/");
	}

}
