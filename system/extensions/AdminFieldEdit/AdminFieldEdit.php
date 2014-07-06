<?php

class AdminFieldEdit extends AdminObjectEdit
{

    protected function setupObject(){

        if(api("input")->get->new){
            $field =  new Field();
            $field->template = "field";

            $this->object = $field;
            $this->title = "New Field";
        }
        else{
            $name = api("input")->get->name;
            $this->object = api("fields")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;
        }

    }


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->label = $field->label;

            if (!is_null($this->object)) {
                $name = $field->get("name");
                $fieldtype->value = $this->object->getUnformatted($name);
                $fieldtype->setObject($this->object);
                $fieldtype->attribute("name", $name);
                $fieldset->add($fieldtype);
            }

        }

        $this->form->add($fieldset);

    }



    public function render()
    {
        $this->addDefaultFields();
        return $this->form->render();
    }

}
