<?php

/**
 * Class FlatbedDateTime
 *
 * Extends PHP DateTime class to add string output compatibility
 * 'echo $page->modified' instead of 'echo $page->modified->format("F j, Y")'
 *
 * Added __toString magic method and $outputFormat variable / setter to facilitate
 *
 */
namespace Flatbed;
class FlatbedDateTime extends \DateTime
{

    protected $outputFormat = "c"; // default to ISO 8601

    public function setOutputFormat($format){
        $this->outputFormat = $format;
    }

    /**
     * Allows FlatbedDatetime to be string cast using default format
     *
     * @return string
     */
    public function __toString()
    {
        return parent::format($this->outputFormat);
    }

    /**
     * allow DateTime format to use the FlatbedDateTime default
     * @param  string $format DateTime compatible format
     * @return string         DateTime in requested format
     */
    public function format($format)
    {
        return parent::format( $format ?? $this->outputFormat );
    }


}
