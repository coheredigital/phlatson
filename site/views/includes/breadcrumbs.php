<div class="breadcrumbs">
    <div class="container">
        <?php $parents = $page->parents() ?>
        <?php if (count($parents) && $page->url != $home->url): ?>
        <?php foreach ($parents as $p): ?>
            <a href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
        <?php endforeach ?>
        <span><?php echo $page->title ?></span>
        <?php endif ?>
    </div>
</div>