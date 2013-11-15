<?php 

// admin scripts and themes (default always needed)
$config->styles->add("{$config->urls->admin}styles/adminTheme.css");
$config->scripts->add("//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js");


// establish the admin pages
$page->title = "Admin";



// if page being editted, what one?
if ($page->requests[1] == "edit") {
	$page->adminTemplate = $config->paths->admin.'markup/edit.php';
}
else{
	$page->adminTemplate = $config->paths->admin.'markup/pagetree.php';
}

include $page->adminTemplate;