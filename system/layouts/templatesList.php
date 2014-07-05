<?php

$templatesList = $templates->all();

$table = $extensions->get("MarkupTable");
$table->setColumns(
    array(
        "Name" => "name",
        "Label" => "label"
    )
);

foreach ($templatesList as $item) {
    $table->addRow(
        array(
            "name" => "<a href='{$adminUrl}templates/edit/?name={$item->name}' >{$item->name}</a>",
            "label" => $item->label
        )
    );
}
$output = $table->render();
$output = "<div class='container'><div class='ui list'>{$output}</div></div>";