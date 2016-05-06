<?php

class MarkupPageList extends Extension
{

    public $rootPage = null;
    public $adminPanel;
    public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page

    protected function renderList(Page $page){

        $table = $this->api("extensions")->get("MarkupTable");

        $children = $page->children();

        $table->setColumns([
           "title" => "title",
            "" => "controls"
        ]);

        foreach ($children as $p) {
            $table->addRow(
                array(
                    "title" => $p->title,
                    "controls" => $this->renderPageControls($p)
                )
            );
        }

        return $table->render();

    }


    protected function renderPageControls(Page $page){

        $admin = $this->api("admin");

        $output = "<div class='page-tree-item-buttons' style='visibility: visible;'>";
        if($page->isEditable()) $output .= "<a class='page-tree-item-button' href='$page->urlEdit'><i class='fa fa-pencil'></i></a>";
        if($page->isViewable()) $output .= "<a class='page-tree-item-button' target='_blank' href='{$page->url}'><i class='fa fa-eye'></i></a>";
        $output .= "</div>";
        return $output;
    }

    public function render()
    {

        if(!$this->rootPage instanceof Page) throw new FlatbedException("No 'rootRage' set in '$this->className'");
        $output = $this->renderList($this->rootPage);
        return $output;
    }

}