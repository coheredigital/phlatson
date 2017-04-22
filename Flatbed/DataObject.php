<?php
namespace Flatbed;
abstract class DataObject extends Object
{


    protected function processSavePath()
    {

        // handle new object creation
        if ($this->isNew()) {
            // TODO - validate parent

            if ($this instanceof Page && !$this->parent instanceof Page) {
                throw new FlatbedException\FlatbedException("cannot create new {$this->className} without valid parent");
            }

            if (!$this->name) {
                throw new FlatbedException\FlatbedException("cannot create new {$this->className} without valid name");
            }

            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }


    }

    protected function saveToFile($path, $filename)
    {
        $file = $path . $filename;
        $json = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($file, $json);
    }



    public function _rename($name)
    {

        if ($name == $this->name) {
            return false;
        }

        $current = $this->path;
        $destination = $this->parent->path . $name . "/";

        rename($current, $destination);

        $this->path = $destination;
        $this->file = $this->path . static::DEFAULT_SAVE_FILE;

        return $this;

    }


    public function _delete()
    {

        // recursively remove all child file and folders before removing self
        $it = new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($this->path);
    }

    public function _save()
    {

        // can't edit system objects
        if( !$this->isEditable() ) return false;

        foreach($this->skippedFields as $fieldname){

            if($this->has($fieldname)) unset($this->data[$key]);

        }
        $this->processSavePath();
        $this->saveToFile($this->path, static::DEFAULT_SAVE_FILE);

        return $tis;

    }

}
