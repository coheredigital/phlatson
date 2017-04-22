<?php include 'includes/head.php' ?>
    <div class="container">
        <h6><strong><?= $page->modified->format("F j, Y") ?></strong></h6>
        <?= $page->markdown ?>
        <?php if ($page->children()): ?>

            <div class="panel panel-default">
                <div class="panel-body">
                    <ul>
                        <?php foreach ($page->children() as $p): ?>
                            <li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>
    </div>
<?php
include 'includes/foot.php';
