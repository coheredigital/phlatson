<?php
namespace Phlatson\Fieldtype\FieltypeMarkdown;
class FieldtypeMarkdown extends Fieldtype
{

    protected function setup()
    {
        require_once "Parsedown.php";
    }

    public function decode($value)
    {
        $parsedown = new \Parsedown;
        return $parsedown->text($value);
    }

    public function encode($value)
    {
        return trim($value);
    }


}