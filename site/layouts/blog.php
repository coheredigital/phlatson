<?php include 'includes/head.inc' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?= $page->content ?>
        <?php if ($page->children): ?>
            <div class="panel panel-default">
                <div class="panel-body">

                        <?php foreach ($page->children as $p): ?>
                            <div>
                                <h3><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h3>
                                <hr/>
                                <?php echo $page->content ?>

                            </div>
                        <?php endforeach ?>

                </div>
            </div>
        <?php endif ?>

        <pre>
            <?php

            $ext =  $extensions->get("FieldtypeDatetime");
            var_dump($ext->name);
            ?>

        </pre>
    </div>
<?php include 'includes/foot.inc'; ?>