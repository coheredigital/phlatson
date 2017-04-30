<?php


$this->respond("/", function($response){

    // redirect user already logged in
    if( $this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}pages/");
    $response->page->layout = $this->api("views")->get('layouts/default');
    $response->page->layout->main .= $this->api("views")->render('partials/header');


});


$this->respond("/pages", function($response){

    // redirect user already logged in
    if( $this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}pages/");
    $response->page->layout = $this->api("views")->get('layouts/default');
    $response->page->layout->main .= $this->api("views")->render('partials/header');

});



$this->respond("/login", function($response){

    if ($response->method = "POST") {
        // TODO : give call back access to request
        $username = $request->post->username;
        $password = $request->post->password;

        // if ($this->api('session')->login($username, $password)) {
        //     $response->redirect("{$this->page->url}/pages");
        // }
    }
    else {

    }

    // redirect user already logged in
    if( $this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}pages/");

    $response->page->layout = $this->api("views")->get('layouts/login');

    $response->append(  $response->page->render() );

});


$this->respond("/logout", function($response){

	$this->api('session')->logout();

	$response->redirect("{$response->page->url}login/");

});