<?php

class InputText {

    protected function render()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }

}