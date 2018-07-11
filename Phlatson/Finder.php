<?php 

namespace Phlatson;

class Finder
{
    protected $root;

    protected $fileMatches = [
        'data.json',
        'published.json',
        'draft.json',
        'autosave.json',
    ];

    public function __construct(string $path)
    {
        // normalize the path
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
            return false;
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
	
	protected function sanitizeFolder(string $folder)
	{
		$folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);
		$folder = trim($folder, "/");
		return "/{$folder}/";
	}
	
	public function getPath(string $folder)
	{
		$folder = $this->sanitizeFolder($folder);
		return $this->root . $folder;
	}

}
