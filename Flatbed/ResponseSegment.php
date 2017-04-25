<?php
namespace Flatbed;

/*

Placeholder class

*/

class ResponseSegment
{

    protected $segment_map;
    protected $type;
    protected $value;

    final public function __construct( string $type, $value )
    {
        $this->value = $this->format($type, $value);
    }


    public function format($type,$value) {

        switch ($this->type) {
            case 'field':
                # code...
                break;
            default:
                return $this->getString();
        }

    }


    protected function getString() {

        

    }


}