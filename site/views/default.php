<?php include 'includes/head.php' ?>
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

$field = new Field;
$field->fieldtype = "FieldtypeText";
$field->input = "InputText";

echo $field->input->render();

// var_dump($field);


include 'includes/foot.php';