<?php
$extensionsList = $extensions->all();

$table = $extensions->get("MarkupTable");
$table->setColumns(
    array(
        "Title" => "title",
        "Type" => "type",
        "" => "description",
        "Version" => "version"
    )
);

foreach ($extensionsList as $item) {
    $table->addRow(
        array(
            "title" => $item->title,
            "type" => $item->type,
            "description" => $item->description,
            "version" => $item->version
        )
    );
}

$output = $table->render();