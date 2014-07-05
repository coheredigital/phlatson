<?php

class MarkupEditForm extends Extension
{
    // array of field markup to be rendered
    public $object;
    public $formID;

    public $tabs = array();

    public function add(MarkupFormtab $element)
    {
        $this->tabs[] = $element;
    }


    public function render()
    {
        $colCount = 0;
        $formTabMenu = "";
        $formTabContent = "";

        $tabCount = 0;
        foreach ($this->tabs as $element) {
            $tabCount++;
            if (is_object($element)) {
                $class = $tabCount == 1 ? "active" : "";
                $colCount += $element->columns;
                $formTabMenu .= "<a class='item tab-item {$class}' data-tab='{$element->id}'>$element->label</a>";

            }
        }
        $formTabMenu = "<div class='ui top attached tabular menu'>{$formTabMenu}</div>";

        $tabCount = 0;
        foreach ($this->tabs as $element) {

            if (is_object($element)) {

                $tabCount++;
                $active = $tabCount == 1 ? true : false;

                $colCount += $element->columns;
                $formTabContent .= $element->render($active);

            }
        }
        $formTabContent = "<div class='ui segment MarkupFormTab'><div class='container'>{$formTabContent}</div></div>";


        // add the form controls
        $formActions = api("extensions")->get("FieldtypeFormActions");
        $formActions->object = $this->object;
        $formActions = $formActions->render();

        $output = "<form id='pageEdit' class='ui form' method='POST' role='form'>" .
            "<div class='container'>$formTabMenu</div>".
            $formTabContent .

            "<div class='container'>$formActions</div>".

            "</form>";

        return $output;


    }


}
