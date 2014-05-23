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

        // just some rough ideas of how I want to handle sort, filter, find etc

        if(1 == 2){

            $pages->find("title==this");
            $pages->filter("!published");
            
        }



        ?>


    </div>
<?php include 'includes/foot.inc'; ?>