<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flatbed - <?php echo $page->title ?></title>
    <link href='http://fonts.googleapis.com/css?family=Raleway:300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $views->url ?>styles/main.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/favicons/manifest.json">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#f46b4b">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<?php $home = $pages->get("/") ?>
<div class="header">
    <div class="container">
        <?php include "breadcrumbs.php" ?>
        <h1><?php echo $page->title ?></h1>
    </div>

</div>
<nav class="main-menu" role="navigation">
    <div class="container">
    <!-- <?= $page->rootParent->url ?> -->
    <?php foreach ($home->children() as $p): ?>

        <?php $class = $p->url == $page->rootParent->url ? "class='active'" : "" ?>
        <a <?php echo $class ?> href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
    <?php endforeach ?>
    </div>
</nav>
<div class="main">