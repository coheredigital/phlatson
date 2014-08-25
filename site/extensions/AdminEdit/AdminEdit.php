<?php

class AdminEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;


    protected function addDefaultFields()
    {

        $fieldset = api("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;

        foreach ($template->fields as $field) {
            $input = $this->getInput($field);
            $fieldset->add($input);
        }

        $this->form->add($fieldset);

    }



    protected function getInput($field)
    {
        $name = $field->name;
        $input = $field->input;
        $input->value = $this->object->get($name);
        $input->label = $field->title;
        $input->attribute("name", $name);

        return $input;
    }

    protected function addFields()
    {
        $this->addDefaultFields();
    }


    private function renderForm(){
        $this->form = api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;
        $this->addFields();
        return $this->form->render();
    }

    public function render()
    {
        $admin = api("admin");
        $admin->output = $this->renderForm();
        $admin->render();
    }


}