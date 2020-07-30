<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
	<link rel="manifest" href="/favicon/site.webmanifest">
	<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#724fff">
	<link rel="shortcut icon" href="/favicon/favicon.ico">
	<meta name="msapplication-TileColor" content="#724fff">
	<meta name="msapplication-config" content="/favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<title><?= $page->template->name != "home" ? "{$page->title} | " : "" ?><?= $home->title ?></title>
	<?php foreach ($config->styles as $file) : ?>
		<link rel="stylesheet" href="<?= $file ?>">
	<?php endforeach; ?>
</head>

<body>
	<?= $page->output->main ?>
</body>

</html>