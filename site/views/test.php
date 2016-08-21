<?php include 'includes/head.php' ?>
    <div class="container">
        <?php
        $field = new Field;
        $field->fieldtype = "FieldtypeText";
        $field->input = "InputText";

        $field->save();

        echo $field->input->render();
        ?>
    </div>
<?php
include 'includes/foot.php';