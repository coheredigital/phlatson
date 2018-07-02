<?php 
namespace Phlatson;

$home = $pages->get("/");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page</title>
    <link rel="stylesheet" href="<?= $views->url ?>styles/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><?= $page->get('title') ?></h1>
        </div>
        <div class="container">
        <div class="menu">
        <?php foreach ($home->children() as $p) : ?>
            <a class="item" href="<?= $p->url ?>"><?= $p->title ?></a>
        <?php endforeach; ?></div>
            
        </div>
    </div>