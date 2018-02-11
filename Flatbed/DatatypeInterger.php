<?php
namespace Flatbed;
class DatatypeInterger extends Datatype
{

    protected $value;

    /**
     * format value for output
     *
     * @param mixed raw input value
     * @return mixed formatted publc facing variable
     */
    public function get($value) : int
    {
        return $this->value;
    }

    public function set($value)
    {
        $this->value;
    }


}
