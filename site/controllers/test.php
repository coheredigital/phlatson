<?php
/*


THIS file MUST do some fundemetal things

    allow overriding of default page and template values. IE, change the view used, overing the returned page
    allow adding of new routes
    allow access to API variables
    allow override of default route / response


Could define more traditional routes here too?

    this could mean that template could by default define a response to the root path of there associated page
    complex retrun types for the response object could be handled in the controller instead of needing to define
    a settings interface in the admin to allow for this

    $this->respond("/", function($request){

        // override default response type to use XML
        $resonse->format("XML");

    });

    also could allow for overriding the behavior of when user acces level fails?

    // IDEA 1
    $this->respond("/", function( $response ){
        // do stuff here
    })->denied(function( $response ){
        // redirect the user to the login page
        $response->redirect("/login");
    });

    // IDEA 2

    $this->respond("/", function($response){
        $response->denied(function(){
            $this->redirect("/some-other/page");
        });
    });

*/

$this->bind('login', function($event){
    r($event);
    r($this);
});

$this->bind('logout', function($event){
    echo "logout";
});

$this->bind('save', function($event){
    echo "saving stuff";
});



// d(1);
r("CONTROLLER FILE");


