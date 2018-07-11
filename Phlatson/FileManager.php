<?php

namespace Phlatson;

class Filemanager
{
    protected $root;

    public function __construct(string $root)
    {
        if (!file_exists($root)) {
            throw new Exceptions\PhlatsonException("Root path must be valid folder, '$root' not found.");
        }

        $this->root = $root;
    }

    public function archive(string $path)
    {
        $path = "{$this->root}{$path}";
        $name = basename($path);

        $zip = new \ZipArchive();
		$zip->open("{$name}.zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    // TODO: Flesh out this concept
    public function saveData($data, string $path, $name = 'data') : bool
    {
        $filepath = ROOT_PATH . $path;
        if (!file_exists($filepath)) {
            throw new Exceptions\PhlatsonException("Path ($filepath) does not exist!");
        }

        $json = json_encode($data);

        $file = "{$filepath}{$name}.json";
        return file_put_contents($file, $json) ? true : false;
    }

    // TODO: Flesh out this concept
    public function getData(string $path, $name = 'data') : ?array
    {
        $filepath = ROOT_PATH . trim($path, '/') . '/';
        $file = "{$filepath}{$name}.json";
        if (!file_exists($file)) {
            return null;
        }
        $data = file_get_contents($file);
        $json = json_decode($data, true);

        return $json;
    }
}
