<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */

class InputSelect extends Input{

    protected $options = [];
    protected $selected = [];


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
    public function addOption($name, $value, $selected = false){

        $this->options[$value] = $name;
        if($selected) $this->selected[$value] = $name;
    }



    /**
     * @param $array
     *
     * Add an array of options for select input
     */
    public function addOptions($array){

        foreach($array as $name => $value){
            $this->addOption($name, $value);
        }
    }

    protected function renderInput()
    {
        $options = $this->renderOptions();
        $output = "<select {$this->attributes}>$options</select>";
        return $output;
    }

} 