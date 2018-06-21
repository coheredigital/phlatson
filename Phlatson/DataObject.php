<?php
namespace Phlatson;
/**
 * DataObjects are Phlatson objects that contain a saveable JsonObject
 */
abstract class DataObject extends PhlatsonObject
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
