<?php

class AdminFieldEdit extends AdminPageEdit
{

    public function setup()
    {
        parent::setup();

        if( $name = api("input")->get->name ){
            $this->page = api("fields")->get($name);
            $this->template = $this->page->template;
            $this->title = $this->page->label;
        }


    }




    public function render()
    {


        $this->addDefaultFields();

        $submitButtons = api("extensions")->get("FieldtypeFormActions");
        $submitButtons->dataObject = $this->page;
        $submitButtonsGroup = api("extensions")->get("MarkupFieldset");
        $submitButtonsGroup->add($submitButtons);

        $this->form->add($submitButtonsGroup);
        return $this->form->render();

    }

}
