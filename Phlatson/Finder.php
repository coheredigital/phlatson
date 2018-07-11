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

    public function getFiles(string $path) : array
    {
        $path = $this->root . $path;

        $files = glob("{$path}*.json");

        return $files;
    }

    public function exists(string $path)
    {
        $path = $this->root . $path;
        $file = "{$path}data.json";

        if (!file_exists($file)) {
            return false;
        }

        return $file;
    }

    public function get(string $path)
    {
        if (!$file = $this->exists($path)) {
            return;
        }

        $jsonObject = new JsonObject($file);
        return $jsonObject;
    }
}
