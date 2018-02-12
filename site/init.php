<?php

/* NOT IMPLEMENTED
loads early in app init process
*/

// IDEA : alternative way to define where admin is
// this could also live in the home.php controller
$this->respond("/admin", function(){
    $response->template = "admin";
});
