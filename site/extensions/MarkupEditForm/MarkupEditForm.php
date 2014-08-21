<?php

class MarkupEditForm extends Extension
{
    // array of field markup to be rendered
    public $object;
    public $formID;
    public $api;

    public $tabs = array();

    public function add(MarkupFormtab $element)
    {
        $this->tabs[] = $element;
        $this->api = extract(api());
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
                $formTabMenu .= "<li class='item'><a class='{$class}' data-tab='{$element->id}'>$element->label</a></li>";

            }
        }
        $formTabMenu = "<ol data-tabs-id='section' class='menu menu-tabs'>{$formTabMenu}</ol>";

        $tabCount = 0;
        foreach ($this->tabs as $element) {

            if (is_object($element)) {

                $tabCount++;
                $active = $tabCount == 1 ? true : false;

                $colCount += $element->columns;
                $formTabContent .= $element->render($active);

            }
        }
        $formTabContent = "<div class='container'><ol data-tabs-id='section' class='tabs'>{$formTabContent}</ol></div>";


        // add the form controls
        $formActions = api("extensions")->get("FieldtypeFormActions");
        $formActions->object = $this->object;
        $formActions = $formActions->render();

        $output = "<form id='pageEdit' class='ui form' method='POST' role='form'>" .
            "<div id='formTabs'><div class='container'>$formTabMenu</div></div>" .
            $formTabContent .
            $formActions .
            "</form>";

        return $output;


    }


}
