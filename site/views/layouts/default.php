<?php

namespace Phlatson;

$home = new Page('/');

// r($page->parent()->parent()->title);
r($page->parents());
$debugbarRenderer = $debugbar->getJavascriptRenderer();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page</title>
    <link rel="stylesheet" href="<?= $views->url ?>styles/main.css?<?= date('U') ?>">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="breadcrumbs">
            <?php foreach ($page->parents() as $p) : ?>
                <a href="<?= $p->url ?>"><?= $p->title ?></a> /
            <?php endforeach; ?>
            </div>

            <h1><?= $page->get('title') ?></h1>
        </div>
        <div class="container">
            <div class="menu">
            <?php foreach ($home->children() as $p) : ?>
                <a class="item" href="<?= $p->url ?>"><?= $p->title ?></a>
            <?php endforeach; ?>
            </div>
		</div>
    </div>
	<?= $output ?>
    </div>
		<div class="container">
		<hr>
		<hr>
	</div>
    <?= $debugbarRenderer->renderHead() ?>
    <?= $debugbarRenderer->render() ?>
</body>
</html>