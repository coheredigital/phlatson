<?php

class AdminPageEdit extends Extension
{
    protected $title;
    protected $form;
    protected $object;

    public function setup()
    {

        Router::get( "/__/pages/edit/(:all)" , function($url){

                $this->object = api("pages")->get($url);
                $this->template = $this->object->template;
                $this->title = $this->object->title;

                api("admin")->render($this,true);
            });

        Router::get( "/__/pages/new/(:all)" , function( $url){


                $this->object = new Page();

                // set the template first so following value can be set properly
                $this->object->template = $url;

                // set parent from get parameter
                $parentUrl = api("request")->get->parent;
                $this->object->parent = $parentUrl;

                $this->title = "New Page";


                api("admin")->render($this,true);
            });

        Router::post( "/__/pages/edit/(:all)" , function( $url){


                $page = api("pages")->get($url);
                $this->object = $page;

                $this->processSave();

            });


    }


    protected function addDefaultFields()
    {

        $fieldset = api::get("extensions")->get("MarkupFormtab");
        $fieldset->label = $this->get("title");

        $template = $this->object->template;
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldtype = $field->type;
            $fieldtype->setObject($this->object);
            $fieldtype->label = $field->label;

            if (!is_null($this->object)) {
                $name = $field->get("name");
                $fieldtype->attribute("name", $name);
                $fieldset->add($fieldtype);
            }

        }

        $this->form->add($fieldset);

    }



    protected function addFilesFields()
    {

        $tab = api("extensions")->get("MarkupFormtab");
        $tab->label = "Files";
        $tab->add($this->getFieldFiles());

        $this->form->add($tab);
    }


    protected function getFieldFiles()
    {

        $fieldtype = api("extensions")->get("FieldtypePageFiles");
        $fieldtype->setObject($this->object);
        $fieldtype->label = "Files";
        $fieldtype->columns = 12;
        $fieldtype->attribute("name", "parent");

        return $fieldtype;
    }




    public function render()
    {

        $this->form = api("extensions")->get("MarkupEditForm");
        $this->form->object = $this->object;

        $this->addDefaultFields();
        $this->addFilesFields();

        return $this->form->render();

    }



    public function processFiles(){
        if (!empty($_FILES)) {
            $uploader = new Upload($this->object);
            $uploader->send($_FILES);
        }
    }

    public function processSave(){

        $this->object->save();
        api::get("session")->redirect(
            api::get("input")->query
        );

    }

}