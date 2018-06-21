<?php
namespace Phlatson;
abstract class Datatype
{

    protected $value;

    /**
     * format value for output
     *
     * @param mixed raw input value
     * @return mixed formatted publc facing variable
     */
    public function get($value)
    {
        return $this->value;
    }

    public function set($value)
    {
        $this->value;
    }

}