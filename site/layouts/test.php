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

        var_dump($extensions->fieldtypes);

        ?>
        <h4>Page Files</h4>
        <ul>

        <?php

        foreach($page->files as $file){
            // var_dump($file);
            echo "<li><a href='$file->url'>$file->name</a></li>";
        }

        ?>

        </ul>


    </div>
<?php include 'includes/foot.inc'; ?>