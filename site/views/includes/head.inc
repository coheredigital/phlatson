<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XTest - <?php echo $page->title ?></title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,200,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $config->urls->layouts ?>styles/main.css">
</head>
<body>
<?php $home = $pages->get("/") ?>


<div class="header">

    <div class="container">
        <div class="row">
            <div class="col col-md-6">
                <h1><?php echo $page->title ?></h1>
            </div>
            <div class="col col-md-6">
                <nav id="nav" class="navbar navbar-default navbar-static-top" role="navigation">

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>


                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav pull-right">
                            <?php foreach ($home->children as $p): ?>
                                <?php $class = $p->url == $page->rootParent->url ? "class='active'" : "" ?>
                                <li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>

                </nav>
            </div>
        </div>
    </div>
</div>
<?php if ($page->parent->template->name != "blog" && $page->template->name != "blog"): ?>
    <nav id="subnav" class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <ul class="nav navbar-nav">
                <?php foreach ($page->rootParent->children as $p): ?>
                    <?php $class = $p->url == $page->rootParent->url ? "class='active'" : "" ?>
                    <li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
                <?php endforeach ?>
            </ul>
        </div>
    </nav>
<?php endif ?>
<?php include "breadcrumbs.inc" ?>
<div id="main">

