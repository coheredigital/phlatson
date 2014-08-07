<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 7/17/14
 * Time: 7:36 PM
 */


class AdminExtensionsList extends Extension {


    protected $output;

    protected function setup()
    {

        $config = api("config");

        api('router')->add(
            new Route( "/{$config->adminUrl}/extensions" , function(){
                $this->render();
            })
        );

    }


    protected function renderFieldsList()
    {

        $config = api("config");

        $fieldsList = api("extensions")->all();
        $table = api("extensions")->get("MarkupTable");
        $table->setColumns(
            array(
                "Name" => "name",
                "Label" => "label",
                "Type" => "type",
            )
        );

        foreach ($fieldsList as $item) {
            $table->addRow(
                array(
                    "name" => "<a href='{$config->urls->root}{$config->adminUrl}/fields/edit/{$item->name}' >{$item->name}</a>",
                    "label" => $item->label,
                    "type" => $item->type // TODO : getting the formatted version of this causes an Exception to be thrown, look into this
                )
            );
        }

        $output = $table->render();

        $controls = "<div class='ui secondary pointing menu'>
                <div class='right menu'>
                    <div class='item'>
                        <div class='ui icon input'>
                            <input type='text' placeholder='Filter...'>
                            <i class='search link icon'></i>
                        </div>
                    </div>
                </div>
            </div>";

        return "<div class='container'>{$controls}{$output}</div>";

    }

    public function render()
    {

        $admin = api("admin");
        $admin->title = "Extensions";
        $admin->output = $this->renderFieldsList();
        $admin->render();

    }

} 