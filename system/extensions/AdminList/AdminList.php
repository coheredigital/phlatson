<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PMs
 */
abstract class AdminList extends Admin implements AdminPage
{

    public $title;
    protected $objectName;
    protected $columns =  [
        "Title" => "title",
        "Name" => "name"
    ];

    protected function setup()
    {
    }


    protected function renderList()
    {

        $objectCollection = $this->api($this->objectName)->all();
        $table = $this->api("extensions")->get("MarkupTable");

        $this->columns["controls"] = "controls";
        $table->setColumns($this->columns);

        foreach ($objectCollection as $object) {
            $table->addRow(
                array(
                    "title" => $object->title,
                    "name" => $object->name,
                    "controls" => $this->renderPageControls($object)
                )
            );
        }

        $output = $table->render();

        return "<div class='container'>{$output}</div>";

    }



    protected function renderPageControls(Object $object){
        $output = "<div class='page-tree-item-buttons' style='visibility: visible;'>";
        if($object->isEditable()) $output .= "<a class='page-tree-item-button' href='{$object->urlEdit}'><i class='fa fa-pencil'></i></a>";
        if($object->isViewable()) $output .= "<a class='page-tree-item-button' target='_blank' href='{$object->url}'><i class='fa fa-eye'></i></a>";
        $output .= "</div>";
        return $output;
    }

    protected function renderControls()
    {
        $output = "<div class='form-actions'>";
        $output .= "<a class='button' href='{$this->route->url}new'>New</a>";
        $output .= "</div>";

        return $output;
    }

    protected function renderHeading()
    {


        $output = "<div id='title'>";
        $output .= "<div class='container'>";
        $output .= "<div class='main-title'>{$this->title}</div>";
        $output .= $this->renderControls();
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    public function render()
    {

        $admin = $this->api("admin");
        $admin->title = $this->title;
        $heading = $this->renderHeading();
        $list = $this->renderList();
        $admin->output = "{$heading}{$list}";
        $admin->render();
    }

} 