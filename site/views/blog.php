<?php include 'includes/head.php' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php
        $children = $page->children()->limit(5)->paginate();
        ?>
        <h4><?= $children->count() ?></h4>
        <?php foreach ($children as $key => $value):?>
            <article>
                <h2><?= $value->name ?></h2>
            </article>
            <hr>
        <?php endforeach ?>

        <?php if ($children->count > $this->limit): ?>
            <a class="button" href="?page">Next</a>
        <?php endif; ?>
    </div>
<?php include 'includes/foot.php';
