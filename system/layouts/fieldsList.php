<?php

$fieldsList = $fields->all();
$table = $extensions->get("MarkupTable");
$table->setColumns(
    array(
        "Name" => "name",
        "Label" => "label",
        "fieldtype" => "fieldtype",
    )
);

foreach ($fieldsList as $item) {
    $table->addRow(
        array(
            "name" => "<a href='{$adminUrl}fields/edit/?name={$item->name}' >{$item->name}</a>",
            "label" => $item->label,
            "fieldtype" => $item->type // TODO : getting the formatted version of this causes an Exception to be thrown, look into this
        )
    );
}

$output = $table->render();

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

$output = "<div class='container'>{$controls}{$output}</div>";