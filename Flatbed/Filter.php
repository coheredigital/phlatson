<?php
namespace Flatbed;
class Filter
{

    /**
     * returns a valid name from the provided string
     * @param $string string
     *
     */
    public static function name($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace(
            '~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
            '$1',
            $string
        );
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), '-', $string);
        $string = strtolower($string);
        $string = trim($string, ' -');

        return $string;
    }

    public static function url($url)
    {
        $url = str_replace("\\", "/", $url);
        $url = trim($url, "/") . "/";
        return "/$url";
    }

    public static function uri($string)
    {
        $string = static::url($string);
        $string = trim($string, "/");
        if (strlen($string) === 0) {
            $string = "";
        }

        return $string;
    }


    public static function path($path)
    {
        // TODO : FIX, closed system, should have to check for double slashes
        $path = str_replace( '\\\\' , DIRECTORY_SEPARATOR, $path);
        $path = str_replace( '\\' , DIRECTORY_SEPARATOR, $path);
        $path = str_replace( "/" , DIRECTORY_SEPARATOR, $path);
        $path = is_file($path) ? $path : $path . DIRECTORY_SEPARATOR;
        return $path;
    }

}
