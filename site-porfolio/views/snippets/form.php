<?php

// return if ID forgotten
if (!isset($id)) return;

$form = $forms->load($id);

if (!$form) return;

echo $form->render();
