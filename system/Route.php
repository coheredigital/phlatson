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
    protected $urlArray;
    protected $method;
    protected $parameters = array();
    protected $parameterValues = array();

    public function __construct( $name, $url , $method ){
        $this->name = $name;
        $this->method = $method;
        $this->url = $url;

        $this->setParameters($url);

    }

    protected function setParameters($url){

        $this->urlArray =  explode( "/", $url );

        $i = 0;
        foreach ( $this->urlArray as $parameter ){

            if($parameter[0] == ":"){
                $key = trim($parameter, ":");

                $this->parameters[$i] = $key;
            }

            $i++;
        }

    }

    public function match($url){



        $urlArray = explode( "/", $url );

        if( $this->urlArray[0] !== $urlArray[0] ){
            return false;
        }

        $i=0;
        foreach ( $urlArray as $parameter ){
            if($this->parameters[$i]){
                $value = trim($parameter, ":");
                $this->parameterValues[$key] = $value;
            }
            elseif( $this->urlArray[$i] !== $urlArray[$i] ){
                return false;
            }
            $i++;
        }

        return true;

    }


    public function execute(){
        call_user_func_array( $this->method , $this->parameterValues );
    }

}

