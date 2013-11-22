<?php 
$admin = new AdminPage($_GET['_url']);
// $admin = $pages->get($input->url);
$adminHome = new AdminPage("/"); // create home page object for simple ref back to admin root

// admin scripts and themes (default always needed)
$config->styles->add("{$config->urls->admin}styles/adminTheme.css");
$config->styles->add("{$config->urls->admin}styles/font-awesome/css/font-awesome.css");
$config->scripts->add("//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js");


include $admin->get('layout');

require_once 'markup/_output.php';

// var_dump($admin);