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

foreach ($extensionsList as $name) {

    $extension = $extensions->get($name);

    $table->addRow(
        array(
            "title" => $extension->title,
            "type" => $extension->type,
            "description" => $extension->description,
            "version" => $extension->version
        )
    );
}

$output = $table->render();