<?php

class MarkupEditForm extends Extension
{
    // array of field markup to be rendered
    public $object;
    public $formID;
    public $api;

    public $items = array();

    public function add($element)
    {
        $this->items[] = $element;
    }

    protected function renderActions(){


        $output .= "<div class='form-actions'>";
        $output .= "<div class='container'>";
        $output .= "<a href='{$this->object->url}' target='_external' class='button'><i class='icon icon-share'></i></a> ";
        $output .= "<button type='submit' class='button button-red'> <i class='icon icon-times'></i></button> ";
        $output .= "<button type='submit' class='button button-green'><i class='icon icon-save'></i> Save </button> ";
        $output .= "</div>";
        $output .= "</div>";
        return $output;

    }

    public function render()
    {
        $colCount = 0;
        $formTabMenu = "";
        $formTabContent = "";

        $tabCount = 0;
        foreach ($this->items as $element) {
            if(!$element instanceof MarkupFormtab) continue;

            $tabCount++;
            if (is_object($element)) {
                $class = $tabCount == 1 ? "active" : "";
                $colCount += $element->columns;
                $formTabMenu .= "<li class='item'><a class='{$class}' data-tab='{$element->id}'>$element->label</a></li>";

            }
        }
        if($tabCount) $formTabMenu = "<ol data-tabs-id='section' class='menu menu-tabs'>{$formTabMenu}</ol>";


        $tabCount = 0;
        foreach ($this->items as $element) {

            if (is_object($element)) {

                $tabCount++;
                $active = $tabCount == 1 ? true : false;

                $colCount += $element->columns;
                $formTabContent .= $element->render($active);

            }
        }
        $formTabContent = "<div class='container'><ol data-tabs-id='section' class='tabs'>{$formTabContent}</ol></div>";


        // add the form controls
        $formActions = $this->renderActions();

        $output = "<form id='pageEdit' class='ui form' method='POST' role='form'>";
        if($formTabMenu) $output .= "<div id='formTabs'><div class='container'>$formTabMenu</div></div>";
        $output .= $formTabContent;
        $output .= $formActions;
        $output .= "</form>";

        return $output;


    }




}
