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
    </div>
<?php

$page->title = "This is a page";
$page->date = "July 1, 2014";

$template = $page->template;

$pageFields = $template->fields;

$parent = $page->parent;

$parentTitle = $parent->title;

//$p = $pages->get("about/sdsdsd");
//$p->rename("cat");


include 'includes/foot.inc';


?>