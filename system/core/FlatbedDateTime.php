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

class FlatbedDateTime extends DateTime
{

    protected $outputFormat = "F j, Y";

    public function setOutputFormat($format){
        $this->outputFormat = $format;
    }

    public function __toString()
    {

        return $this->format($this->outputFormat);
    }

}