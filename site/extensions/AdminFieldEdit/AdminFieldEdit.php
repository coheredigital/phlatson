<?php

class AdminFieldEdit extends AdminObjectEdit
{

    public static function getInfo() {
        return array(
            'title' => 'ColorPicker',
            'version' => 104,
            'summary' => 'Choose your colors the easy way.',
            'href' => 'http://processwire.com/talk/topic/865-module-colorpicker/page__gopid__7340#entry7340',
            'requires' => array("FieldtypeColorPicker")
        );
    }

    protected function setupObject(){

        if( api::get("input")->get->new ){
            $field = new Field();
            $field->template = "field";

            $this->object = $field;
            $this->title = "New Field";
        }
        else{
            $name = api::get("input")->get->name;
            $this->object = api::get("fields")->get($name);
            $this->template = $this->object->template;
            $this->title = $this->object->title;
        }

    }


    protected function addDefaultFields()
    {

        $fieldset = api::get("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->get("template");
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->label = $field->label;

            if (!is_null($this->object)) {
                $name = $field->name;
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
