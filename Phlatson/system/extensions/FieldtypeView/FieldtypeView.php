<?php
namespace Phlatson;
class FieldtypeView extends Fieldtype
{

	public function getOutput($value)
    {
        
        $file = $this->api('views')->get($value);
        if (file_exists($file)) return $file;
        return '';
    }

	public function getEdit($value)
    {
        return $value;
    }

}