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
$output->main = $table->render();
$controls = "<div class='ui secondary pointing menu'>
                <a class='item' href='{$page->url}/edit?new=1'>New</a>
                <div class='right menu'>
                    <div class='item'>
                        <div class='ui icon input'>
                            <input type='text' placeholder='Filter...'>
                            <i class='search link icon'></i>
                        </div>
                    </div>
                </div>
            </div>";

$output->main = "<div class='container'>{$controls}{$output->main}</div>";