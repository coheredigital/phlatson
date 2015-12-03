<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */
class AdminListUsers extends Extension
{


    protected $output;

    protected function setup()
    {

        $usersRoute = new Route;
        $usersRoute
            ->name("users")
            ->path("users")
            ->parent($this->api("admin")->route)
            ->callback(
                function () {
                    $this->render();
                }
            );

        $this->api('router')->add($usersRoute);

    }


    protected function renderUsersList()
    {

        $config = $this->api("config");

        $table = $this->api("extensions")->get("MarkupTable");
        $table->setColumns(
            array(
                "Username" => "username"
            )
        );

        $users = $this->api("users")->all();
        foreach ($users as $item) {
            $table->addRow(
                array(
                    "username" => "<a href='{$config->urls->admin}users/edit/{$item->name}' >{$item->name}</a>"
                )
            );
        }

        $output = $table->render();


        $controls .= "<div class='form-actions'>";
        $controls .= "<div class='container'>";
        $controls .= "<a class='button' href='{$config->urls->admin}fields/new'>New</a>";
        $controls .= "</div>";

        return "<div class='container'>{$output}{$controls}</div>";

    }

    public function render()
    {

        $admin = $this->api("admin");
        $admin->title = "Fields";
        $admin->output = $this->renderUsersList();
        $admin->render();

    }

} 