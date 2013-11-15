<?php 

// admin scripts and themes (default always needed)
$config->styles->add("{$config->urls->admin}styles/adminTheme.css");
$config->scripts->add("//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js");


// establish the admin pages
$page->title = "Admin";



// if page being editted, what one?
if ($page->requests[1] == "page") {
	if ($page->requests[2] == "edit") {
		$page->adminTemplate = $config->paths->admin.'markup/pageEdit.php';
	}
	elseif($page->requests[2] == "new"){

	}
	
}
elseif($page->requests[1] == "fields"){
	$page->adminTemplate = $config->paths->admin.'markup/fieldsList.php';
}
else{
	$page->adminTemplate = $config->paths->admin.'markup/pagetree.php';
}

include $page->adminTemplate;