<?php
namespace Flatbed;
class InputHidden extends Input {

    public function setup()
    {
        $this->api('config')->styles->add("{$this->url}{$this->name}.css");
    }

    protected function renderInput()
    {
        $this->attribute("type", "hidden");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $output = "<input {$this->attributes}>";
        return $output;
    }

}