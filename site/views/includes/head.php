<?php // add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 1);
ref::config('validHtml', true); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flatbed - <?php echo $page->title ?></title>
    <link href='http://fonts.googleapis.com/css?family=Raleway:300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $config->urls->views ?>styles/main.css">
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