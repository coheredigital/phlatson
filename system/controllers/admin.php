<?php 


// check if user is logged in yet
// 
// TODO :  templates should be able to define access level 
// and the automatic action to take when user has no access
// in this case, redirect to "login"

if ($user->isGuest() && $response->segment(1) != "login") {
	$response->redirect("{$page->url}login/");
}
if($user->isLoggedin() && $response->segment(1) === "login") {
	$response->redirect($page->url);
}

$page->layout = $views->get('layouts/default');

if ($response->segment(1) === "login") {
	$page->layout = $views->get('layouts/login');
	// $page->template->set('view', "admin.login");
}
else {

	$page->layout->main .= $views->render('partials/header');


	
	$page->layout->main .= $views->render('partials/user-menu');
}