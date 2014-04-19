<?php include 'includes/head.inc' ?>
    <div class="container">
        <!-- PAGE CONTENT -->
        <?= $page->content ?>
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
        <?php endif ?>

        <pre>
            <?php

            $ext =  $extensions->get("FieldtypeDatetime");
            var_dump($ext->name);
            var_dump($ext->title);
            var_dump($ext->directory);
            ?>

        </pre>
    </div>
<?php include 'includes/foot.inc'; ?>