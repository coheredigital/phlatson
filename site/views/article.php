<?php include 'partials/head.php' ?>
    <div class="container">
        <?= $page->content ?>
        <!-- PAGE CONTENT -->
        <?php if ($page->children): ?>
            <?php foreach ($page->children as $p): ?>
                <h3><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h3>
                <p><?php echo $p->content ?></p>
            <?php endforeach ?>
        <?php endif ?>
    </div>
<?php include 'partials/foot.php';