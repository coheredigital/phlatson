<?php if (count($page->parents)): ?>
<div class="breadcrumbs">
    <?php foreach ($page->parents as $p): ?>
        <a href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
    <?php endforeach ?>
    <!-- <span><?php echo $page->title ?></span> -->
</div>
<?php endif ?>