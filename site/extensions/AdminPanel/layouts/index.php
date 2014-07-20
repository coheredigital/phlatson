<?php

if ($input->get->logout == 1) {
    $session->logout();
    $session->redirect($config->urls->root . $this->location);
}
if ($user->isGuest() && $page->layout != "login") {
    $page->layout = "login";
}


// admin scripts and themes (default always needed)
$config->styles->add("{$this->url}styles/adminTheme.css");
$config->styles->add("{$this->url}styles/semantic.min.css");
$config->styles->append("{$this->url}styles/font-awesome-4.1.0/css/font-awesome.css");
$config->scripts->prepend("{$this->url}scripts/semantic.min.js");
$config->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");
$config->scripts->add("{$this->url}scripts/jquery-sortable.js");
$config->scripts->add("{$this->url}scripts/init.js");

$output = new SimpleArray();
$markup = new MarkupFile("{$this->path}layouts/includes/main-menu.php");
$output->header = $markup->render();

if ($page->extension) {
    $output->main = $page->render();
}
else {
    include $page->layoutFile;
}


if( $user->isLoggedin() ){
    require_once 'main.php';
}
else{
    require_once 'includes/login-layout.php';
}