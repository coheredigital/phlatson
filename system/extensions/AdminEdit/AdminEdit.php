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

        $input->label = $field->title;
        // todo: improve select value handling
        if($input instanceof ReceivesOptions){
            $fieldtype = $field->type;
            $array = $fieldtype->options();
            $input->addOptions($array);
        }


        $input->value = $this->object->getUnformatted("$field->name");
        $input->attribute("name", $field->name);

        return $input;
    }

    protected function addFields()
    {
        $this->addDefaultFields();
    }

    public function processSave()
    {


        $this->object->save();
        app("admin")->addMessage("Page '{$this->object->url} saved successfully'  ");
        app("router")->redirect( $this->object->urlEdit );

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