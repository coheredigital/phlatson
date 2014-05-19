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
//                $p = new Page;
//                $p->template = "default";
//                $p->parent = $page;
//                $p->name = "test-page";
//
//                $p->title = "This page is brand new!";
//                $p->content = "Blah blah blah.";
//
//                $p->save();

            ?>


    </div>
<?php include 'includes/foot.inc'; ?>