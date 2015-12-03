<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 05/02/2015
 * Time: 10:51 AM
 */

class Logger extends App {

    private $file;
    private $data;
    private $rootPath;

    function __construct()
    {
        $this->rootPath = $this->api("config")->paths->assets . "logs/";
    }

    protected function getFile($type)
    {

        $this->file = "{$this->rootPath}{$type}.json";

        if (!is_file($this->file)) {

            $this->data["info"] = [];
            $this->data["info"]['created'] = (int) date("U");
            $this->data["info"]['modified'] = (int) date("U");
            file_put_contents(
                $this->file,
                json_encode($this->data, JSON_PRETTY_PRINT)
            );
        }

        $this->data = json_decode(file_get_contents($this->file), true);
    }

    public function add($type, $message){

        $this->getFile($type);

        $log = [];
        $log["timestamp"] = (int) date("U");
        $log["message"] = $message;

        // backtrace
//        $backtrace = debug_backtrace();
//        $log["backtrace"] = $backtrace;

        $this->data["entries"][] = $log;

        $this->data["info"]["modified"] = $log["timestamp"];
        $this->save();
    }

    private function save(){
        file_put_contents(
            $this->file,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );
    }

}