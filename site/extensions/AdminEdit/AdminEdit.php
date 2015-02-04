<?php

class AdminEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;


    protected function addDefaultFields()
    {

        $fieldset = app("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;

        foreach ($template->fields as $field) {
            $fieldtype = $this->getFieldInterface($field);
            $fieldset->add($fieldtype);
        }

        $this->form->add($fieldset);

    }



    protected function getFieldInterface(Field $field)
    {

        $fieldtype = $field->type;
//        $fieldtype->settings($field->_settings);
        $fieldtype->label = $field->title;
        $fieldtype->value = $this->object->get($field->name);
        $fieldtype->attribute("name", $field->name);

        return $fieldtype;
    }

    protected function addFields()
    {
        $this->addDefaultFields();
    }

    private function renderForm(){
        $this->form = app("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;
        $this->addFields();
        return $this->form->render();
    }

    public function render()
    {
        $admin = app("admin");
        $admin->output = $this->renderForm();
        $admin->render();
    }


}