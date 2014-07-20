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

        $this->urlArray =  explode( "/",  trim($url, "/") );

        $i = 0;
        foreach ( $this->urlArray as $parameter ){

            if( $parameter[0] === "<" ){
                $this->parameters[$i] = true;
            }

            $i++;
        }

    }

    public function match($url){


        $urlArray = explode( "/", trim($url, "/") );

        $i=0;
        foreach ( $urlArray as $parameter ){
            if( $this->parameters[$i] || !$this->parameters[$i+1] && !isset($this->urlArray[$i]) ){
                $this->parameters[$i] = $parameter;
            }
            elseif( isset($this->urlArray[$i]) && $this->urlArray[$i] !== $urlArray[$i] ){
                return false;
            }
            $i++;
        }

        return true;

    }


    public function execute(){
        call_user_func_array( $this->method , $this->parameters );
    }

}

