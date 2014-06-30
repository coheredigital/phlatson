<?php include 'includes/head.inc' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?= $page->content ?>
        <?php $page->children ?>
        <?php if ($page->children): ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul>
                        <?php foreach ($page->children as $p): ?>
                            <li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        <?php
        endif;


        $p = $pages->get("/about-us/blah");
        var_dump($p->url);

        $root = $p->rootParent;
        var_dump($root->url);


        ?>







    </div>
<?php include 'includes/foot.inc'; ?>