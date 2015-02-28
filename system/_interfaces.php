<?php


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