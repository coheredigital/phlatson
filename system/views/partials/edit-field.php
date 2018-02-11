<?php

$fieldname = $response->segment(2);
$field = $fields->get($fieldname);

$templateFields = $field->get('template')->get('fields');

$inputs = new Flatbed\ObjectCollection;

foreach ($templateFields as $templateFieldName => $templateField) {

    $input = $templateField->get('input');
    $input->label = $templateField->title;
    // todo: improve select value handling
    if($input instanceof ReceivesOptionsInterface){
        $fieldtype = $field->fieldtype;
        $input->addOptions($fieldtype->options());
    }
    $input->value = $field->data($templateFieldName);
    $input->attribute("name", $templateFieldName);


    $inputs->append($input);
}


?>
<div class="container">
    <form action="">
        <?php foreach ($inputs as $i): ?>
            <?= $i->render() ?>
        <?php endforeach ?>
        <br>
        <div class='form-actions'>
            <a href='{$field->url}' target='_external' class='button button-view'><i class='fa fa-share'></i></a>
            <button type='submit' class='button button-delete'> <i class='fa fa-times'></i></button>
            <button type='submit' class='button button-save'><i class='fa fa-save'></i> Save</button>
        </div>

    </form>
</div>
