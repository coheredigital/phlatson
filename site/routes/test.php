<?php

d('routes');


$this->respond('/', function(){
    d("BLAH");
});

$this->respond('/respond-to-this')
    ->callback(function(){
        d("POOP");
    });

$this->respond('/pam')->callback(function(){
     d("wife");
});

$this->respond('/pam')->callback(function(){
     d("wife");
});
