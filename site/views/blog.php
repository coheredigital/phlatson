<?php include 'includes/head.php' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php
        $children = $page->children()->limit(10)->paginate();
        ?>
        <?php foreach ($children as $p):?>
            <article>
                <h3><?= $p->title ?></h3>
                <h6><?= $p->name ?></h6>
                <a href="<?= $p->url ?>">Read more...</a>
            </article>
            <hr>
        <?php endforeach ?>

        <?php if ($children->pageCount): ?>
            <?php if ($children->nextPage): ?>
                <a class="button button-primary u-pull-right" href="?page=<?= $children->nextPage ?>">>></a>
            <?php endif ?>

            <?php if ($children->previousPage): ?>
                <a class="button button-primary u-pull-left" href="?page=<?= $children->previousPage ?>"><<</a>
            <?php endif ?>
        <?php endif; ?>
    </div>
<?php include 'includes/foot.php';