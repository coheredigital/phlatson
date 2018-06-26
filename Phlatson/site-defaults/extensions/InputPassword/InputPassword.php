<?php
namespace Phlatson;
class InputPassword extends Input {

    protected function renderInput()
    {
        $this->attribute("type", "password");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $output = "<input {$this->attributes}>";
        return $output;
    }

}