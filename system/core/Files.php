<?php

/*

Get files recusively through folder by checking the existing "files.xml"

can be set to start at a parent folder ex: "/about-us/"
and can be limited to a recursive depth (0 for no limit | 1 for one, no recursive)

files shoulde be stored in a key => value array where key is /path/to/folder/ and value is filename.ext


 */

class Files extends ObjectArray
{

    protected $dataFile = "files.xml";
    protected $dataFolder = "content/";
    protected $checkSystem = true;
    protected $singularName = "File";


    public function load($folder = "", $depth = 0)
    {
        $path = $this->api('config')->paths->site . "{$this->dataFolder}{$folder}/";

        if (!is_dir($path)) {
            return false;
        } // return fallse if the directory passed in is invalid

        $rdi = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $rii = new RecursiveIteratorIterator($rdi);
        // set the depth limit
        if ($depth) {
            $rii->setMaxDepth($depth);
        }

        // find only direct matches to our desired "data file"
        $ri = new RegexIterator($rii, "/{$this->dataFile}/");


        $fileList = array();
        foreach ($ri as $key => $value) {

            $key = str_replace(DIRECTORY_SEPARATOR, "/", $key);
            $key = str_replace($this->api('config')->paths->content, "", $key);

            $key = str_replace($this->dataFile, "", $key);
            $key = (string)trim($key, "\\");

            $fileList["$key"] = simplexml_load_file($value->getRealPath());
        }

        $array = array();
        foreach ($fileList as $xml) {

            foreach ($xml->xpath("//file") as $k => $v) {
                $folder = $key;
                $path = $this->api('config')->paths->site . $folder;
                $array[] = new File("$folder", "$v->filename");
            }
        }
        $this->data = $array;
    }

    // load all file, not depth limit, could be very slow
    public function all($depth = false)
    {
        $this->load();
        return $this->data;
    }


}