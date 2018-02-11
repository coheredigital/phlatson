<?php
namespace Flatbed;
// interface ObjectInterface {
//
//     // public function get( string $name );
//     // public function set( string $name, $value );
// }

// interface ProvidesOptions
// {
//     public function options();
// }

interface ReceivesOptions
{
    public function addOption($name, $value, $selected = false);
    public function addOptions($array);
}

interface AdminPage
{
    public function render();
}

interface FieldtypeSortable {
    public function sort();
}
