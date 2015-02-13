<?php

class AdminEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;


    protected function addDefaultFields()
    {

        $fieldset = registry("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;


        foreach ($fields as $field) {
            $fieldtype = $this->getFieldInterface($field);
            $fieldset->add($fieldtype);
        }

        $this->form->add($fieldset);

    }



    protected function getFieldInterface(Field $field)
    {

        $fieldtype = $field->type;
        $fieldtype->label = $field->title;
        $fieldtype->value = $this->object->get("$field->name");
        $fieldtype->attribute("name", $field->name);

        return $fieldtype;
    }

    protected function addFields()
    {
        $this->addDefaultFields();
    }

    public function processSave()
    {

        if(!is_object($this->object)){
            throw new Exception("Fatal Error, no object defined, cannot save");
        }

        $this->object->processInputData();
        $this->object->save();
        registry("session")->redirect( registry("request")->url );

    }


    private function renderForm(){
        $this->form = registry("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;
        $this->addFields();
        return $this->form->render();
    }

    public function render()
    {
        $admin = registry("admin");
        $admin->output = $this->renderForm();
        $admin->render();
    }


}