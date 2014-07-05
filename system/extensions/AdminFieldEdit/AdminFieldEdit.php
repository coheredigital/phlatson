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
        return $this->form->render();
    }

}
