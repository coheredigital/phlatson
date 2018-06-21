<?php
namespace Flatbed;

abstract class FlatbedObject extends Flatbed
{

    const DEFAULT_SAVE_FILE = "data.json";
    const BASE_FOLDER = '';
    const BASE_URL = '';

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
            $filepath = '/site/' . $this::BASE_FOLDER . $path . $this::DEFAULT_SAVE_FILE;
            $this->data = new JsonObject($filepath);
        }

        // return if no data set (this is a new page)
        // the follow could initializes existing pages
        if (!$this->data) {
            return;
        }
        if ($templateName = $this->data->get("template")) {
            $this->template = new Template($templateName);
        }

    }

    public function get($key)
    {


        switch ($key) {
            case 'name':
                $value = \basename($this->data->path);
                break;
            case 'modified':
                $value = $this->data->getModifiedTime();
                $value = new FlatbedDateTime("@$value");
                break;
            
            default:
                $value = $this->data->get($key);

                if ($this->template instanceof Template && $this->template->hasField($key)) {

                    $field = $this->template->getField($key);
                    // TODO : Testing field formatting, replace
                    if ($field['fieldtype'] == "FieldtypeDatetime") {
                        $value = new \DateTime("@{$value}");
                    }

                }

                
                break;
        }

        return $value;

    }

}
