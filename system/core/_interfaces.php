<?php

interface ObjectInterface {

    // public function get( string $name );
    // public function set( string $name, $value );
}

interface ProvidesOptions
{
    public function options();
}

interface ReceivesOptions
{
    public function addOption($name, $value, $selected = false);
    public function addOptions($array);
}

interface AdminPage
{
    public function render();
}

interface ViewableObject
{
	// bool check for permission to view and existing view files
	// public function isViewable();
	// public function _render();
}

interface RenderInterface
{
    // public function _render();
}

interface FieldtypeSortable {

    public function sort();

}
