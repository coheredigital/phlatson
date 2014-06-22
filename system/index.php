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
$config->styles->add("{$config->urls->systemLayouts}styles/adminTheme.css");
$config->styles->add("{$config->urls->systemLayouts}styles/semantic.min.css");

$config->scripts->add("{$config->urls->systemLayouts}scripts/jquery-2.1.1.min.js");
$config->scripts->add("{$config->urls->systemLayouts}scripts/jquery-sortable.js");
$config->scripts->add("{$config->urls->systemLayouts}scripts/semantic.min.js");

// admin pages
if ($output = $page->render()) {
} else {
    if(is_file($page->layout)){
        include $page->layout;
    }
}
if( $user->isLoggedin() ){
    require_once $config->paths->systemLayouts . 'includes/default-layout.php';
}
else{
    require_once $config->paths->systemLayouts . 'includes/login-layout.php';
}
