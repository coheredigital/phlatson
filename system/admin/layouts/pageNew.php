<?php

$newTemplateName = $input->get->template;
$template = $templates->get($newTemplateName);


// $form = $extensions->get("MarkupEditForm");

// $fieldset = $extensions->get("MarkupFieldset");
// $fieldset->label = "Add New: {$template->label}";

// $nameField = $extensions->get("FieldtypeText");
// $nameField->attribute("name", "name");
// $nameField->label = "Page Name";
// $nameField->columns = 12;

// $templateField = $extensions->get("FieldtypeTemplate");
// $templateField->attribute("name", "template");
// $templateField->label = "Template";
// $templateField->columns = 12;


// $fieldset->add($nameField);
// $fieldset->add($templateField);
// $form->add($fieldset);

$form = $extensions->get("AdminPageEdit");
$form->setNew();
$form->setup();

$output = $form->render();
