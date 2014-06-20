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


        $p = $pages->get("/");
        var_dump($p);

        $list = $pages->all();

        foreach($list as $p){
            echo "$p->title | $p->directory <br>";
        }
//        var_dump($list);

        ?>







    </div>
<?php include 'includes/foot.inc'; ?>