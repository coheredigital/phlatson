<?php

namespace ProcessWire;

dump($page);
dump($page->files());

$output = $this->render('snippets/site-title');

$output .= $this->render('snippets/intro', [
	"value" => $page->get('content')
]);

$output .= $this->render('snippets/form', [
	"value" => $this->render('forms/contact')
]);

echo $this->render('/layouts/default', [
	'styles' => [
		'https://fonts.googleapis.com/css?family=Roboto:400,700',
		'https://fonts.googleapis.com/css?family=Montserrat:700,900',
		'/site-portfolio/views/styles/main.css'
	],
	'content' => $output
]);