<?php include 'includes/head.inc' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?php if ($page->children): ?>
            <?php foreach ($page->children as $p): ?>
                <h3><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h3>
                <p><?php echo $p->content ?></p>
            <?php endforeach ?>
        <?php endif ?>
    </div>
<?php include 'includes/foot.inc';