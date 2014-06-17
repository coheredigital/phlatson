<?php

class MarkupPageList extends Extension
{

    public $rootPage;
    public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page

    protected function setup()
    {
        api('config')->scripts->add($this->url . "{$this->className}.js");
    }


    public function renderPageTitle(\Page $page)
    {
        $output .= "<div class='page-tree-item ui segment'>";
        $output .= "<div class='tiny icon ui buttons right floated'>";
        $output .= "<a class='ui button' href='" . api('config')->urls->root . api("config")->adminUrl . "/pages/new/?parent=/" . $page->directory . "&template=test&new=1'><i class='icon plus'></i></a>";
        $output .= "<a class='ui button' target='_blank' href='{$page->url}'><i class='icon unhide'></i></a>";
        $output .= "</div>";
        $output .= "<i class='icon reorder'></i><a class='page-item-edit-link' href='" . api('config')->urls->root . api("config")->adminUrl . "/pages/edit/?name=" . $page->directory . "'>{$page->title}</i></a>";
        $output .= "<div class='page-tree-item-buttons'>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }

    public function renderPageItem(\Page $page)
    {
        $output = $this->renderPageTitle($page);
        if (count($page->children)) {
            $output .= $this->renderPageList($page->children);
        }

        $output = "<li class='page-tree-group'> {$output}</li>";
        return $output;
    }

    private function renderPageList($pages)
    {
        $output = "";
        foreach ($pages as $p) {
            $output .= $this->renderPageItem($p);
        }
        $output = "<ul class='page-tree-list'> {$output} </ul>";
        return $output;
    }

    public function render()
    {
        $output = $this->renderPageItem($this->rootPage);
        $output = "<div class='page-tree'><ul class='page-tree-list page-tree-root'>{$output}</ul></div>";
        return $output;
    }

}