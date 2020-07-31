<?php

namespace ProcessWire;


// $page->output->main .= $page->render("title");
$page->profile_image = $page->images->getTag("profile");

$page->form = $page->snippet("form", [
	"id" => "contact"
]);

// render markup
$page->output->main .= $page->render('summary');
$page->output->main .= $page->render('form');
