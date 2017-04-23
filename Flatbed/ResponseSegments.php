<?php
namespace Flatbed;

/*

Placeholder class

*/

class ResponseSegments
{


    protected $response;
    protected $segments = [];
    protected $named_segments = [];



    public function __construct( Response $response )
    {
        $this->response = $response;
    }


    public function __toString() {
        return implode("/", $this->segments);
    }

    public function __get($name) {

        if (is_int( (int) $name )) {
            $index = (int) $name - 1;
            return $this->segments[$index];
        }
        else {
            return $this->named_segments[$name];
        }

    }

}