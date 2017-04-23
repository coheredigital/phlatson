<?php 

// check for submitted form
if ($request->post->username && $request->post->password) {
    
    $username = $request->post->username;
    $password = $request->post->password;

    if ($session->login($username, $password)) {
        $response->redirect("$page->url/pages");
    }

}