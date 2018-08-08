<?php
namespace Phlatson\Fieldtype\FieltypeMarkdown;
class FieldtypeMarkdown extends Fieldtype
{

    protected function setup()
    {
        require_once "Parsedown.php";
    }

    public function output($value)
    {
        $parsedown = new \Parsedown;
        return $parsedown->text($value);
    }

    public function set($value)
    {
        return trim($value);
    }


}