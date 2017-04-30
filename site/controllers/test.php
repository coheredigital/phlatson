<?php
/*
Could define more traditional routes here too?

    this could mean that template could by default define a response to the root path of there associated page
    complex retrun types for the response object could be handled in the controller instead of needing to define
    a settings interface in the admin to allow for this

    $this->respond("GET:/", function($request){

        // override default response type to use XML
        $resonse->format("XML");

    });


*/

$this->respond('PUT:/view:section/int:page', function($request){

    

});

$this->map('pages', function() {

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