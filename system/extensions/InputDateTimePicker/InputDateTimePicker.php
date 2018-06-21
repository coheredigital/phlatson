<?php
namespace Phlatson;
class InputDateTimePicker extends Input {

    protected function renderInput()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $attributes = $this->getAttributes();
        $output = "<input $attributes>";
        return $output;
    }

}