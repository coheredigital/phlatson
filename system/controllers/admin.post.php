<?php 

// check for submitted form
if ($this->request->post->username && $this->request->post->password) {
    
    $username = $this->request->post->username;
    $password = $this->request->post->password;

    if ($this->session->login($username, $password)) {
        $response->redirect("{$this->page->url}/pages");
    }

}