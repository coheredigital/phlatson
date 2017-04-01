<?php include 'includes/head.php' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php

        $children = $page->children;
        $children->sort("modified");


        if ($children): ?>
            <?php foreach ($children as $p):?>
                <article>
                    <header>
                        <h5 class="title"><a href="<?= $p->url ?>"><?= $p->title ?></a></h5>
                        <h6><strong><?= $p->modified ?></strong></h6>
                    </header>
                </article>

            <?php endforeach ?>
        <?php endif ?>
    </div>
<?php include 'includes/foot.php';
