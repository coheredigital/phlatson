<?php 

namespace Phlatson;
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once(__DIR__ . '/vendor/autoload.php');
// testing image sizer server concepts
r($_GET);
r($config);
$image_url = $_GET["image"];
$image_path = realpath(__DIR__ . "/site/pages/$image_url");

function resize_image($file, $w, $h, $crop=false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

r($image_url);
r($image_path);

$img = resize_image($image_path, 200, 200);

r($img);


$file = '../image.jpg';
$type = 'image/jpeg';
header('Content-Type:'.$type);
header('Content-Length: ' . filesize($file));
readfile($file);