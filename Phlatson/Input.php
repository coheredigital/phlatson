<?php
namespace Phlatson;
/**
 * Class Input
 *
 * Responsible for creation and rendering of Inputs
 */
abstract class Input extends Extension implements RenderInterface
{

    public $value;
    public $label;
    public $field; // field this input belongs to
    public $object; // object (page/field/template) that the above field belongs to


    /**
     * @param $name
     * @param bool $value
     * @return $this|bool
     *
     * Getter/Setter for field attribut values, sets when $name/value supplied
     * Gets when just $name supplied, returns false if no attribute exists
     *
     */
    final public function attribute(string $name, $value = false)
    {

        if ($value) {
            $this->data["attributes"][$name] = (string)$value;
            return $this;
        }

        if (!isset($this->attributes[$name])) return false;
        return $this->attributes[$name];

    }


    /**
     * @param $input
     * @return string
     *
     * Wraps a raw input in standard markup for consistency, called by render on input request
     *
     */
    final protected function renderWrapper($input)
    {

        $columns = $this->settings->columns ? $this->settings->columns : 12;

        $fieldName = $this->attribute("name");
        $output = "<div data-fieldname='$fieldName' class='field field-{$this->name} {$this->name} column column-{$columns}'>";

        if ($this->label !== false) {
            $output .= "<label class='field-label' for='{$this->name}'>";
            $output .= $this->label ? $this->label : $this->name;
            $output .= "</label>";
        }

        $output .= "<div class='field-input' for='{$this->name}'>";

        $output .= "$input";
        $output .= "</div>";

        $output .= "</div>";

        return $output;
    }

    /**
     * @return String
     *
     * should return a raw Input field to be placed inside an Input Wrapper
     *
     */
    abstract protected function renderInput();


    /**
     * @return String
     *
     * Returns the final output, primarily used by admin editing pages
     *
     */
    final public function render()
    {


        $output = $this->renderInput();
        $output = $this->renderWrapper($output);

        return $output;
    }

    /**
     * Render input if requested as string, because why not
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * returns the attributes array
     * @return array
     */
    public function getAttributes()
    {
        return $this->data["attributes"];
    }
    /**
     * get attributes array as HTML compatible key=value string
     * @return string
     */
    public function getAttributesString()
    {
        $string = "";

        $attributes = $this->getAttributes();
        foreach ($attributes as $key => $value) {
            $string .= "{$key}='$value' ";
        }
        return trim($string);

    }

    public function get( string $name )
    {
        switch ($name) {
            case 'attributes':
                return $this->getAttributesString();
            default:
                return parent::get($name);
        }
    }


}
