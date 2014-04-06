<div id="breadcrumbs">
    <div class="container">
        <?php $parents = $page->parents() ?>
        <?php if (count($parents) && $page->url != $home->url): ?>
            <ol class="breadcrumb">


                <?php foreach ($parents as $p): ?>
                    <li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
                <?php endforeach ?>
                <li><?php echo $page->title ?></li>

            </ol>
        <?php endif ?>
    </div>
</div>