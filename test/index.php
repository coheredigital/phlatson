<?php

include 'C:\xampp\htdocs\xpages\system\core\_functions.php';

$object = new DataObject();
$object->load("C:/xampp/htdocs/xpages/test/data.json");

$array = new DataArray();
$array->load("C:/xampp/htdocs/xpages/test/data.json");


$book = array(
    "title" => "Lord of the Rings",
    "pulished" => 39202874,
    "price" => 19.95
);

$books = [
    [
        "title" => "Lord of the Rings",
        "pulished" => 39202874,
        "price" => 19.95
    ],
    [
        "title" => "Lord of the Rings",
        "pulished" => 39202874,
        "price" => 19.95
    ],
    [
        "title" => "Lord of the Rings",
        "pulished" => 39202874,
        "price" => 19.95
    ]
];
$prices = [
    [
        1,2,3,4,5,6,7
    ],
    [
        2,2,2,2,2,2,2
    ],
    [
        5,5,5,5,55
    ]
];


$object->books = $books;
$array->books = $books;

$object->prices = $prices;
$array->prices = $prices;

$object->save("object");
$array->save("array");


var_dump($object->data);
echo "<hr>";
var_dump($array->data);

class DataArray {

    const DATA_FILE = "data.json";

    public $path;
    public $file;

    public $data = array();


    public function load($file)
    {

        // check site path first
        if (!is_file($file)) return false;

        $this->file = $file;
        $this->path = normalizePath(str_replace($this::DATA_FILE,"",$file));
        $this->data = json_decode(file_get_contents($file) ,  true);

    }

    public function save($filename)
    {

        $saveData = json_encode($this->data, JSON_PRETTY_PRINT);

        file_put_contents( $this->path . $filename . ".json" , $saveData );

    }

    public function get($name)
    {

        return $this->data[$name];

    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {

        $this->data[$name] = $value;

    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

}

class DataObject extends DataArray {



    public function load($file)
    {

        // check site path first
        if (!is_file($file)) return false;

        $this->file = $file;
        $this->path = normalizePath(str_replace($this::DATA_FILE,"",$file));
        $this->data = json_decode(file_get_contents($file));

    }


    public function get($name)
    {
        return $this->data->{$name};
    }


    public function set($name, $value)
    {
        $this->data->{$name} = $value;
    }


}