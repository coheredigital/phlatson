<?php


$this->bind('login', function($event){
    var_dump($event);
    echo "login callback: $event";
});


$this->login("awesome");