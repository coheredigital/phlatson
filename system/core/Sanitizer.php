<?php

class Sanitizer extends Core
{

    /**
     * returns a valid name from the provided string
     * @param $string string
     *
     */
    public function name( $string ){

        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), '-', $string);
        $string = strtolower($string);

        return trim($string, ' -');

    }

    public function directory($directory){

        $directory = normalizeUrl($directory);

        if(substr( $directory, 0, 1 ) !== "/"){
            $directory = "/" . $directory;
        }

        return $directory;
    }

    public function url($url){
        $url = str_replace("\\", "/", $url);
        $url = trim($url, "/");
        return $url;
    }

}
