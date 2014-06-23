<?php

class AdminFieldEdit extends AdminPageEdit
{

    public function setup()
    {
        parent::setup();

        if( $name = api("input")->get->name ){
            $this->object = api("fields")->get($name);
            $this->title = $this->object->label;
        }


    }




    public function render()
    {


        $this->addDefaultFields();

        $submitButtons = api("extensions")->get("FieldtypeFormActions");
        $submitButtons->dataObject = $this->object;
        $submitButtonsGroup = api("extensions")->get("MarkupFieldset");
        $submitButtonsGroup->add($submitButtons);

        $this->form->add($submitButtonsGroup);
        return $this->form->render();

    }

}
