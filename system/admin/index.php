<?php 

if ($input->get->logout == 1) {
	$session->logout();
	$session->redirect($config->urls->root.$config->adminUrl);
}
if ($user->isGuest() && $page->name != "login") {
	$session->redirect($config->urls->root.$config->adminUrl."/login");
}

$adminHome = new AdminPage("/"); // create home page object for simple ref back to admin root

if ($page->directory == $adminHome->directory) {
	$pages->find("name=content");

}
// admin scripts and themes (default always needed)

$config->styles->add("{$config->urls->admin}styles/adminTheme.css");
$config->styles->add("{$config->urls->admin}styles/font-awesome/css/font-awesome.css");
$config->scripts->add("//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js");
$config->scripts->add("{$config->urls->admin}scripts/plugins.js");
$config->scripts->add("{$config->urls->admin}styles/uikit/js/uikit.js");

// admin pages 
if ($output = $page->render()) {
}
else{
	include $page->get('layout');	
}


require_once 'includes/output.php';