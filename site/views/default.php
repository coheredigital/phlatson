<?php include 'partials/head.php' ?>
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
    </div>
<?php include 'partials/foot.php';
