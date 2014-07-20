<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 8:33 PM
 */

class Route {

    protected $name;
    protected $url;
    protected $method;
    protected $parameters = array();

    public function __construct( $name, $url , $method ){
        $this->name = $name;
        $this->method = $method;
        $this->url = $url;

        $this->setParameters($url);

    }

    protected function setParameters($url){

        $parameters =  explode( "/", $url );

        $i = 0;
        foreach ( $parameters as $parameter ){

            if($parameter[0] == ":"){
                $key = trim($parameter, ":");

                $this->parameters[$i] = $key;
            }

            $i++;
        }

    }

    public function isMatch($url){

        

    }


}

