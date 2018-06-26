<?php 


$allFields = $fields->all();
$table = $extensions->get("MarkupTable");

$table->setColumns([
    "Title" => "title",
    "Name" => "name"
]);

foreach ($allFields as $field) {
    $table->addRow(
        array(
            "title" => $field->isEditable() ? "<a href='{$page->url}fields/{$field->name}'>{$field->title}</a>" : $field->title,
            "name" => $field->name,
        )
    );
}

?>
<div class="container">
    <?= $table->render() ?>
</div>