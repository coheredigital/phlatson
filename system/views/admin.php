<?php 

$config->styles->add("/system/views/styles/admin.css");
$config->styles->add("https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css");

$config->scripts->add("{$this->url}scripts/jquery-sortable.js");
$config->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
$config->scripts->add("{$this->url}scripts/main.js");
$config->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true);



if ($response->segment(1) === "login") {


	if ($response->method == "POST" && $request->post->username && $request->post->password) {
		
		$username = $request->post->username;
		$password = $request->post->password;


		if ($session->login($username, $password)) {
			$response->redirect($page->url);
		}

	}


	$config->styles->add("/system/views/styles/login.css");
	$page->layout = $views->get('layouts/login');
}


if ($page->messages) {
	$page->layout->main .= $views->get('partials/messages')->render();
}

switch ($response->segment(1)) {
	case 'fields':
		if ($response->segment(2)) {
			$page->layout->main .= $views->render('partials/edit-field');
		}
		else {
			$page->layout->main .= $views->render('partials/list-fields');
		}
		break;
	
	case 'pages':
		if ($response->segment(2)) {
			$page->layout->main .= $views->render('partials/edit-page');
		}
		else {
			$page->layout->main .= $views->render('partials/page-tree');
		}
		break;
}


echo $page->layout->render();