<?php

namespace Phlatson;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page->title ?> | Phlatson</title>
    <link rel="stylesheet" href="<?= $view->rootUrl ?>styles/framework.css?<?= date('U') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Mono">
</head>

<body class="font-default pb4">
    <div class="page-header bg-brand color-white py2 mb2">
        <div class="container">
            <?= $view->render("/partials/breadcrumbs") ?>
            <h1><?= $page->get('title') ?></h1>
            <?= $view->render("/partials/site-navigation") ?>
        </div>
    </div>
    <?= $output ?>
    </div>
</body>

</html>