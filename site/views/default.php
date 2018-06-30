<?php 
namespace Phlatson;

$home = $pages->get("/");
r(count($home->children()));
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
            <?php foreach ($home->children() as $p): ?>
                <?php r($p->url); ?>
                <a href="<?= $p->url ?>"><?= $p->url ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container">
        <table>
            <tr>
                <th>$views->url</th>
                <td><?= $views->url ?></td>
            </tr>
            <tr>
                <th>$pages->url</th>
                <td><?= $pages->url ?></td>
            </tr>
            <tr>
                <th>$page->template->view->url</th>
                <td><?= $page->template->view->url ?></td>
            </tr>
            <tr>
                <th>$page->template->url</th>
                <td><?= $page->template->url ?></td>
            </tr>
            <tr>
                <th>$page->url</th>
                <td><?= $page->url ?></td>
            </tr>

        </table>
        <?php 
        r($pages->get("/")->children());
        r($this);
        r($view);
        r($request);
        ?>
    </div>

</body>
</html>
