<?php

namespace ProcessWire;

// default styles and scripts
$config->styles->append('https://fonts.googleapis.com/css?family=Roboto:400,700');
$config->styles->append('https://fonts.googleapis.com/css?family=Montserrat:700,900');
// $config->styles->append('https://fonts.googleapis.com/css?family=Roboto+Mono');
$config->styles->append('/site/templates/styles/main.css');

// add home to api
$home = $pages->get('/');
$wire->set('home', $home);

// define page output container
$page->output = new \StdClass;

// default header rendering
$page->output->main = $page->snippet('site-title');

$page->meta("name", "store thing");

$page->meta("name");

$page