<?php


$this->respond("/", function($request, $response){

    // redirect user not logged in
    if( !$this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}login/");
    $response->page->layout = $this->api("views")->get('layouts/default');
    $response->page->layout->main .= $this->api("views")->render('partials/header');

});

$this->respond("/pages", function($request, $response){

    // redirect user not logged in
    if( !$this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}login/");
    $response->page->layout = $this->api("views")->get('layouts/default');
    $response->page->layout->main .= $this->api("views")->render('partials/header');

});


$this->respond("/login", function($request, $response){
    // redirect user already logged in
    if( $this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}pages/");
    $response->page->layout = $this->api("views")->get('layouts/login');
    $response->append(  $response->page->render() );
});


$this->respond("POST/login", function($request, $response){



    if ($request->method == "POST") {
        // TODO : give call back access to request
        $username = $request->post->username;
        $password = $request->post->password;

        if ($this->api('session')->login($username, $password)) {
            $response->redirect("{$response->page->url}/pages");
        }
    }
    else {
        // redirect user already logged in
        if( $this->api('user')->isLoggedin() ) $response->redirect("{$response->page->url}pages/");

        $response->page->layout = $this->api("views")->get('layouts/login');

        $response->append(  $response->page->render() );
    }



});


$this->respond("/logout", function($request, $response){

	$this->api('session')->logout();

	$response->redirect("{$response->page->url}login/");

});
