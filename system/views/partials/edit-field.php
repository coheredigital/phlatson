<?php 

$fieldname = $request->segment(2);
$field = $fields->get($fieldname);

$form = $extensions->get("MarkupEditForm");
$form->object = $field;


$fieldset = $extensions->get("MarkupFormTab");
$fieldset->label = "Edit";


$templateFields = $field->get('template')->get('fields');

foreach ($templateFields as $templateFieldName => $templateField) {

    $input = $templateField->get('input');
   
    $input->label = $templateField->title;

    // todo: improve select value handling
    if($input instanceof ReceivesOptions){
        $fieldtype = $field->fieldtype;
        $input->addOptions($fieldtype->options());
    }

    $input->value = $field->getUnformatted($templateFieldName);
    $input->attribute("name", $templateFieldName);
    
    $fieldset->add($input);
    var_dump($input);
}

$form->add($fieldset);

?>
<div class="container">
    <?= $form->render() ?>
</div>