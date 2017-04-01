<?php include 'includes/head.php' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php

        $children = $page->children;
        $children->sort("modified")->limit(5)->paginate();

         ?>
            <?php foreach ($children as $key => $value):?>
                <article>
                    <header>
                        <h5 class="title"><a href="<?= $value->url ?>"><?= $value->title ?></a></h5>
                        <h6><strong><?= $value->modified ?></strong></h6>
                    </header>
                </article>
                <hr>
            <?php endforeach ?>

        <?php if ($children->count > $this->limit): ?>
            <a class="button" href="?page">Next</a>
        <?php endif; ?>
    </div>
<?php include 'includes/foot.php';
