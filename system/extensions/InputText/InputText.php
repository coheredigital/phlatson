<?php
namespace Flatbed;
class InputText extends Input {

    protected function renderInput()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $output = "<input {$this->attributes}>";
        return $output;
    }

}