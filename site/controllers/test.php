<?php


/*

Could define more traditional routes here too?


*/

$this->respond('PUT:/view:section/int:page', function($request){

    

});

$this->respondGroup('pages', function() {

    $this->respond('GET:/edit/page:', function($request){

        

    });

    $this->respond('POST:/edit/page:', function($request){

        

    });

});


$this->bind('login', function($event){
    r($event);
    d($this);
});

$this->bind('logout', function($event){
    echo "logout";
});

$this->bind('save', function($event){
    echo "saving stuff";
});