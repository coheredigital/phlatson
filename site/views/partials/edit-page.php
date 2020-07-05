<?php

$pagename = $request->get->page;

$pageEdit = $pages->get($pagename);

// var_dump($pageEdit->get('template'));

if (!$pageEdit) {
    throw new Phlatson\\Exception("Page: {$pagename} not found");
}

$template = $pageEdit->get('template');
if (!$template) {
    throw new Phlatson\\Exception("Template not valid");
}

$templateFields = $template->get('fields');

$inputs = new Phlatson\ObjectCollection;

foreach ($templateFields as $templateFieldName => $templateField) {

    $input = $templateField->get('input');
    $input->label = $templateField->title;

    // todo: improve select value handling
    if($input instanceof ReceivesOptionsInterface){
        $fieldtype = $field->fieldtype;
        $input->addOptions($fieldtype->options());
    }
    $input->value = $templateField->data($templateFieldName);
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
