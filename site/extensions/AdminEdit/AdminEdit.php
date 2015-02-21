<?php

class AdminEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;


    protected function addDefaultFields()
    {

        $fieldset = app("extensions")->get("MarkupFormTab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;


        foreach ($fields as $field) {
            $fieldtype = $this->getFieldInput($field);
            $fieldset->add($fieldtype);
        }

        $this->form->add($fieldset);

    }



    protected function getFieldInput(Field $field)
    {

        $input = $field->input;
        $input->field = $field;
        $input->object = $this->object;

//        $input->label = $field->title;

        $input->value = $this->object->get("$field->name");
        $input->attribute("name", $field->name);

        return $input;
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
        app("session")->redirect( app("request")->url );

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