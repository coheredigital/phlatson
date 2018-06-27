<?php namespace Phlatson ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page</title>
    <link rel="stylesheet" href="
use Phlatson\Pages;
<?= $page->template->view->url ?>">
    <link rel="stylesheet" href="/site/views/styles/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><?= $page->get('title') ?></h1>
        </div>
    </div>
    <div class="container">
        <table>
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
        

        r($this);
        r($view);

        r($request);
        ?>
    </div>

</body>
</html>
