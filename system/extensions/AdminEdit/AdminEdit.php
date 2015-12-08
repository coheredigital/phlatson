<?php

class AdminEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;


    protected function addDefaultFields()
    {

        $fieldset = $this->api("extensions")->get("MarkupFormTab");
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

        $this->processInputData();

        if($this->object->save()){
            $this->api("admin")->addMessage("'{$this->object->name}' {$this->object->className} saved successfully'  ");
            $this->api("router")->redirect( $this->object->urlEdit );
        }

    }

    protected function processInputData()
    {

        $post = $this->api("request")->post;

        // loop through the templates available fields so that we only set values
        // for available fields and ignore the rest
        $fields = $this->object->template->fields;


        foreach ($fields as $field) {
            $name = $field->name;
            $value = isset($post->{$name}) ? $post->{$name} : $this->object->getUnformatted("$name");
            $value = $field->type->getSave($value);
            $this->object->set($name, $value);
        }


    }

    private function renderForm(){
        $this->form = $this->api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;
        $this->addFields();
        return $this->form->render();
    }

    public function render()
    {
        $admin = $this->api("admin");
        $admin->output = $this->renderForm();
        $admin->render();
    }


}