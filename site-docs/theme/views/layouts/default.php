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
    <link rel="stylesheet" href="/site/views/styles/framework.css?<?= date('U') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,900;1,700&family=Roboto+Mono&family=Roboto:wght@400;700&display=swap">
</head>

<body class="font-default pb4">
    <div class="page-header color-brand bg-white py2 mb2">
        <div class="container">
            <?= $this->render("/partials/breadcrumbs") ?>
            <h1><?= $page->get('title') ?></h1>
            <?= $this->render("/partials/site-navigation") ?>
        </div>
    </div>
    <?= $output ?>
    </div>
</body>

</html>