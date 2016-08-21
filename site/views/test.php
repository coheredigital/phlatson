<?php // include 'includes/head.php' ?>
    <div class="container">
        <?php
        $field = new Field;
        $field->name = "test";
        $field->fieldtype = "FieldtypeText";
        $field->input = "InputText";

        var_dump($field->rootPath);
        var_dump($field->name);
        var_dump($field->path);

        // $field->save();
        echo "<br>";
        echo "<br>";
        echo '=====================================================';

        $f = $fields->create("summary");
        var_dump($f->name);
        var_dump($f->className);

        echo "<br>";
        echo "<br>";
        echo '=====================================================';
        echo "<br>";
        echo "<br>";
        $title = $fields->get("title");
        var_dump($title->rootPath);
        var_dump($title->path);
        var_dump($title->file);
        var_dump($title->name);
        var_dump($title->route);
        echo basename(dirname($title->file));
        ?>
    </div>
<?php
include 'includes/foot.php';