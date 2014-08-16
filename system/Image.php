<?php

class Image extends File
{

    protected $original = true;

    protected $imageCopy;

    private $imageData;

    protected $alternateName; // name the edited made to the image

    protected $appliedResize = [];

    // store applied filters in order to be used in file name if no image recipe name provided
    protected $appliedFilters = [
        // "blur_3",
        // "brightness_-20,
        // "invert"
    ];


    // output settings
    protected $quality = 90;


    public function __construct($page, $name)
    {
        parent::__construct($page, $name);
        $this->setImageInfo();
    }

    public function setImageInfo()
    {

        $info = getimagesize($this->file);
        $this->width = $info[0];
        $this->height = $info[1];
        if ($this->type = $info[2]) { // file type returned from getimagesize()
            $this->mimeType = $info["mime"];
        } else {
            if (function_exists("exif_imagetype")) {
                $this->type = exif_imagetype($this->file);
            }
        }

    }


    public function edit($name = null)
    {

        $this->imageCopy = clone $this;
        $this->imageCopy->original = false;


        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->imageCopy->imageData = imagecreatefromjpeg($this->file);
                break;
            case IMAGETYPE_GIF:
                $this->imageCopy->imageData = imagecreatefromgif($this->file);
                break;
            case IMAGETYPE_PNG:
                $this->imageCopy->imageData = imagecreatefrompng($this->file);
                break;
        }


        return $this->imageCopy;
    }

    public function resize($width, $height, $crop = false)
    {

        $this->appliedResize = [
            "width" => $width,
            "height" => $height,
            "crop" => $crop
        ];

        $image_resize = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $image_resize,
            $this->imageData,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $this->width,
            $this->height
        ); // samepled from original

        $this->imageData = $image_resize;

        return $this;
    }

//    public function crop(){
//
//        imagecrop($this->imageData);
//
//    }

    /*
     * Image Effects
     * */

    /**
     *    Invert image
     * @return object
     **/
    function invert()
    {
        imagefilter($this->imageData, IMG_FILTER_NEGATE);
        $this->trackFilter(__FUNCTION__);
        return $this;
    }

    /**
     *    Adjust brightness (range:-255 to 255)
     * @return object
     * @param $level int
     **/
    function brightness($level)
    {
        imagefilter($this->imageData, IMG_FILTER_BRIGHTNESS, $level);
        $this->trackFilter(__FUNCTION__, $level);
        return $this;
    }

    /**
     *    Adjust contrast (range:-100 to 100)
     * @return object
     * @param $level int
     **/
    function contrast($level)
    {
        imagefilter($this->imageData, IMG_FILTER_CONTRAST, $level);
        $this->trackFilter(__FUNCTION__, $level);
        return $this;
    }

    /**
     *    Convert to grayscale
     * @return object
     **/
    function grayscale()
    {
        imagefilter($this->imageData, IMG_FILTER_GRAYSCALE);
        $this->trackFilter(__FUNCTION__);
        return $this;
    }

    /**
     *    Adjust smoothness
     * @return imageObject (copy of original)
     * @param $level int
     **/
    function smooth($level)
    {
        imagefilter($this->imageData, IMG_FILTER_SMOOTH, $level);
        $this->trackFilter(__FUNCTION__, $level);
        return $this;
    }

    /**
     *    Emboss the image
     * @return imageObject (copy of original)
     **/
    function emboss()
    {
        imagefilter($this->imageData, IMG_FILTER_EMBOSS);
        $this->trackFilter(__FUNCTION__);
        return $this;
    }

    /**
     *    Apply sepia effect
     * @return imageObject (copy of original)
     **/
    function sepia()
    {
        imagefilter($this->imageData, IMG_FILTER_GRAYSCALE);
        imagefilter($this->imageData, IMG_FILTER_COLORIZE, 90, 60, 45);
        $this->trackFilter(__FUNCTION__);
        return $this;
    }

    /**
     *    Pixelate the image
     * @return imageObject (copy of original)
     * @param $size int
     **/
    function pixelate($size)
    {
        imagefilter($this->imageData, IMG_FILTER_PIXELATE, $size, true);
        $this->trackFilter(__FUNCTION__, $size);
        return $this;
    }

    /**
     *    Blur the image using Gaussian filter
     * @return imageObject (copy of original)
     * @param $selective bool
     **/
    function blur($selective = false)
    {
        imagefilter($this->imageData, $selective ? IMG_FILTER_SELECTIVE_BLUR : IMG_FILTER_GAUSSIAN_BLUR);
        $this->trackFilter(__FUNCTION__, $selective ? "Selective" : null);
        return $this;
    }

    /**
     *    Apply sketch effect
     * @return object
     **/
    function sketch()
    {
        imagefilter($this->imageData, IMG_FILTER_MEAN_REMOVAL);
        $this->trackFilter(__FUNCTION__);
        return $this;
    }

    /**
     *    Rotate the image by set degree
     * @return object
     **/
    function rotate($angle)
    {
        $this->imageData = imagerotate($this->imageData, $angle, 255);
        $this->trackFilter(__FUNCTION__, $angle);
        return $this;
    }



    /**
     *    Apply sketch effect
     * @return object
     * @param $selective bool
     *
     *  requires PHP 5.5+
     **/
//    function flip($direction) {
//
//        switch($direction){
//            case "horizontal":
//                imageflip( $this->imageData, IMG_FLIP_HORIZONTAL);
//                break;
//            case "vertical":
//                imageflip( $this->imageData, IMG_FLIP_VERTICAL);
//                break;
//            case "both":
//                imageflip( $this->imageData, IMG_FLIP_BOTH);
//                break;
//        }
//
//        $this->trackFilter( __FUNCTION__ , ucfirst( $direction ) );
//        return $this;
//    }


    // gets an image based on a predefined size and set of parameters
    public function getAlternate($name)
    {

    }

    // remove image alternative (useful when the edits applied have changed and image alt needs to be updated)
    public function removeAlternate($name)
    {

    }

    public function trackFilter($name, $value = null)
    {
        $this->appliedFilters[] = "{$name}{$value}";
    }

    function save()
    {

        $location = api("config")->paths->cache . $this->page->directory;

        // create save name
        $name = rtrim($this->name, $this->ext);

        // add size
        $name .= $this->appliedResize["width"] . "x" . $this->appliedResize["height"] . ".";

        // add filters
        foreach ($this->appliedFilters as $filter) {

            $name .= $filter . ".";

        }

        $name .= $this->ext; // append file extension


        // create directory if not exist
        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }


        switch ($this->type) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->imageData, $location . DIRECTORY_SEPARATOR . $name, $this->quality);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->imageData, $location . DIRECTORY_SEPARATOR . $name);
                break;
            case IMAGETYPE_PNG:
                imagepng(
                    $this->imageData,
                    $location . DIRECTORY_SEPARATOR . $name,
                    -1
                ); // use default compiled into the zlib library
                break;
        }


        $this->url = $this->url = api("config")->urls->cache . $this->page->directory . "/" . rawurlencode($name);

        return $this;
    }


}