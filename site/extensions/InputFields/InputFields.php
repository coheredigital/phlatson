<?php

class InputFields extends Input implements ReceivesOptions
{

    protected $options = [];


    protected function renderOptions()
    {

        $output = "";
        foreach ($this->options as $value => $text) {
            $selected = $this->value == $value ? "selected='selected'" : null;
            $output .= "<option $selected value='$value'>$text</option>";
        }
        return $output;
    }


    /**
     * @param $name
     * @param $value
     * @param bool $selected
     *
     * Add options for select input
     */
    public function addOption($name, $value, $selected = false)
    {

        $this->options[$value] = $name;
        if ($selected) $this->selected[$value] = $name;
    }


    /**
     * @param $array
     *
     * Add an array of options for select input
     */
    public function addOptions($array)
    {

        foreach ($array as $name => $value) {
            $this->addOption($name, $value);
        }
    }



    protected function renderInput()
    {
        app('config')->styles->add($this->url . "{$this->className}.css");
        app('config')->scripts->add($this->url . "{$this->className}.js");


        array_unshift($this->options, "Choose a field to add...");


        $this->addOption("Choose a field to add...", null);
        $fieldAdd = $this->renderOptions();
        $fieldAdd = "<select>$fieldAdd</select>";

        if (count($this->value)) {
            foreach ($this->value as $field) {

                // retrieve the field object because "$this->value" will return an unformatted value
                $output .= "<div class='item' >
                            <div class='header' >
                                {$field->label}
                            </div>
                            <div>{$field->name}</div>
                            <input type='hidden' name='" . $this->field->name . "[{$field->name}]' value='{$columns}' >
						</div>";
            }
        }

        $output = "	$fieldAdd
	                <div class='field-list'>
						{$output}
					</div>";
        return $output;
    }

}