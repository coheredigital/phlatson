<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/26/14
 * Time: 9:26 PM
 */



class TemplatingPlates extends Extension {


    public $title;
    public $output;


    protected function setup()
    {
        $config = api("config");

        require_once("Plates/Engine.php");
        require_once("Plates/Template.php");
        require_once("Plates/Extension/ExtensionInterface.php");
        require_once("Plates/Extension/Escape.php");
        require_once("Plates/Extension/Batch.php");
        require_once("Plates/Extension/Nest.php");
        require_once("Plates/Extension/URI.php");
        require_once("Plates/Extension/Asset.php");

        $engine = new \League\Plates\Engine($config->paths->layouts);
        $plates = new \League\Plates\Template($engine);

        api("plates", $plates); // register api variable

    }

    public function render()
    {
        extract(api());
        include "layout.php";
    }

}