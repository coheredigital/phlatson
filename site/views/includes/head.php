<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XTest - <?php echo $page->title ?></title>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,200,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
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
    <?php foreach ($home->children as $p): ?>
        <?php $class = $p->url == $page->rootParent->url ? "class='active'" : "" ?>
        <a <?php echo $class ?> href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
    <?php endforeach ?>
    </div>
</nav>
<?php if ($page->parent->template->name != "blog" && $page->template->name != "blog"): ?>
    <nav class="sub-menu" role="navigation">
        <div class="container">
        <?php foreach ($page->rootParent->children as $p): 
            $class = $p->url == $page->rootParent->url ? "class='active'" : "";
            ?><a <?php echo $class ?> href="<?php echo $p->url ?>"><?php echo $p->title ?></a><?php endforeach ?>
        </div>
    </nav>
<?php endif ?>

<div class="main">