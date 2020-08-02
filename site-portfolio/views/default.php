<?php

namespace Phlatson;

// dump($page);

echo $this->render('/layouts/default', [
	'styles' => [
		'https://fonts.googleapis.com/css?family=Roboto:400,700',
		'https://fonts.googleapis.com/css?family=Montserrat:700,900',
		'/site-portfolio/views/styles/main.css'
	],
	'content' => 'test'
]);