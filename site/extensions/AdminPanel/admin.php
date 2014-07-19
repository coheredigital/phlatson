<?php

if ($input->get->logout == 1) {
    $session->logout();
    $session->redirect($config->urls->root . $config->adminUrl);
}
if ($user->isGuest() && $page->name != "login") {
    $session->redirect($config->urls->root . $config->adminUrl . "/login");
}

// when user is logged in but does not define a page, go to page tree
if(!$user->isGuest() && !$input->request[1]){
    $session->redirect($config->urls->root . $config->adminUrl . "/pages");
}

$adminHome = $pages->get($config->adminUrl); // create home page object for simple ref back to admin root



// admin scripts and themes (default always needed)
$config->styles->add("{$config->urls->layouts}admin/styles/adminTheme.css");
$config->styles->add("{$config->urls->layouts}admin/styles/semantic.min.css");
$config->styles->append("{$config->urls->layouts}admin/styles/font-awesome-4.1.0/css/font-awesome.css");

$config->scripts->prepend("{$config->urls->layouts}admin/scripts/semantic.min.js");
$config->scripts->prepend("{$config->urls->layouts}admin/scripts/jquery-1.11.1.min.js");
$config->scripts->add("{$config->urls->layouts}admin/scripts/jquery-sortable.js");

$config->scripts->add("{$config->urls->layouts}admin/scripts/init.js");


/* SETUP MARKUP STORAGE */
api::register("output", new SimpleArray() );
$output = api::get("output");

$markup = new MarkupFile("{$config->paths->layouts}admin/includes/main-menu.php");
$output->header = $markup->render();

// admin pages
if ($page->extension) {
    $output->main = $page->render();
}
else {
    include $page->layout;
}
if( $user->isLoggedin() ){
    require_once $config->paths->layouts . 'admin/includes/default-layout.php';
}
else{
    require_once $config->paths->layouts . 'admin/includes/login-layout.php';
}
