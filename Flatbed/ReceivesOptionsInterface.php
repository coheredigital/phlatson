<?php
namespace Flatbed;

interface ReceivesOptionsInterface
{
    public function addOption($name, $value, $selected = false);
    public function addOptions($array);
}
