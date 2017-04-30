<?php 

// check if user is logged in yet
// 
// TODO :  templates should be able to define access level 
// and the automatic action to take when user has no access
// in this case, redirect to "login"


// if($this->response->segment(1) === "logout") {
// 	$this->session->logout();
// 	$this->response->redirect("{$this->page->url}login/");
// }

// if ($this->user->isGuest() && $this->response->segment(1) != "login") {
// 	$this->response->redirect("{$this->page->url}login/");
// }

// if($this->user->isLoggedin() && $this->response->segment(1) === "login") {
// 	$this->response->redirect($this->page->url);
// }

// $this->page->layout = $this->views->get('layouts/default');

// // login page is requested
// if ($this->response->segment(1) === "login") {
// 	$this->page->layout = $this->views->get('layouts/login');
// 	// $this->page->template->set('view', "admin.login");
// }
// else {
// 	$this->page->layout->main .= $this->views->render('partials/header');
// }