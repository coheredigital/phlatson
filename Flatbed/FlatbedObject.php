<?php
namespace Flatbed;

abstract class FlatbedObject extends Flatbed
{

    const DEFAULT_SAVE_FILE = "data.json";
    const DATA_FOLDER = '';
    

    // main data container, holds data loaded from JSON file
    protected $data;
    protected $template;

    protected $defaultFields = [];

    // prep to have a system to turn fromatting on and off TODO: use this, lol
    protected $enableFormatting = false;

    public function __construct($path = null)
    {
        
        if ($path) {
            
            // normalize
            $path = "/" . trim($path, "/") . "/";
            $filepath = '/site/' . $this::DATA_FOLDER . $path . $this::DEFAULT_SAVE_FILE;
            $this->data = new JsonObject($filepath);
        }

        if ($templateName = $this->data->get("template")) {
            $this->template = new Template($templateName);
        }

    }



}
