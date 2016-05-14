<?php include 'includes/head.inc' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php

        $children = $page->children;
        $children->sort("modified");


        if ($children): ?>
            <?php foreach ($children as $p):?>
                <article>
                    <header>
                        <h3 class="title"><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h3>
                        <h6><?php echo $p->modified ?></h6>
                    </header>


                    <?php echo $p->content ?>
                </article>

            <?php endforeach ?>
        <?php endif ?>
    </div>
<?php include 'includes/foot.inc';