<?php

namespace Phlatson;

$home = new Page('/');
$debugbarRenderer = $debugbar->getJavascriptRenderer();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page->title ?> | Phlatson</title>
    <link rel="stylesheet" href="<?= $view->rootUrl ?>styles/main.css?<?= date('U') ?>">
    <link rel="stylesheet" href="<?= $view->rootUrl ?>styles/templates/home.css?<?= date('U') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Mono">
</head>
<body>
	<?= $output ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>
    hljs.initHighlighting();
    </script>
    <div class="container">
    <nav class="site-navigation">
    <?php foreach ($page->children() as $p) : ?>
        <a class="uppercase mr1 pt1 inline-block" href="<?= $p->url ?>"><?= $p->title ?></a>
    <?php endforeach; ?>
    </nav>
    </div>
</body>
</html>