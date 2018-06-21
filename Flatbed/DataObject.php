<?php
namespace Flatbed;
/**
 * DataObjects are Flatbed objects that contain a saveable JsonObject
 */
abstract class DataObject extends FlatbedObject
{

    public function rename($name)
    {
    }

    public function delete()
    {
    }

    public function save()
    {
        return $this;
    }

}
