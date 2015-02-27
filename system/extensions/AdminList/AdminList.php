<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PMs
 */
abstract class AdminList extends Admin
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

        $objectCollection = app($this->objectName)->all();
        $table = app("extensions")->get("MarkupTable");
        $table->setColumns($this->columns);

        foreach ($objectCollection as $object) {
            $table->addRow(
                array(
                    "name" => "<a href='{$this->route->url}edit/{$object->name}' >{$object->name}</a>",
                    "title" => "<a href='{$this->route->url}edit/{$object->name}' >{$object->title}</a>"
                )
            );
        }

        $output = $table->render();

        return "<div class='container'>{$output}</div>";

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

        $admin = app("admin");
        $admin->title = $this->title;
        $heading = $this->renderHeading();
        $list = $this->renderList();
        $admin->output = "{$heading}{$list}";
        $admin->render();
    }

} 