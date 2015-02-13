<?php

class MarkupPageList extends Extension
{

    public $rootPage;
    public $adminPanel;
    public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page

    protected function setup()
    {
        app('config')->scripts->add($this->url . "{$this->className}.js");
    }


    public function renderPageTitle(Page $page)
    {
        $output .= "<div class='page-tree-item'>";
        $output .= "<i class='icon icon-circle'></i> <a class='page-tree-item-edit' href='" . app('config')->urls->admin . "pages/edit/" . $page->directory . "'>{$page->title}</i></a>";
        $output .= "<div class='page-tree-item-buttons'>";
        $output .= $this->renderDropdown($page);

        $output .= "<a class='button' target='_blank' href='http://" . app("config")->hostname . "{$page->url}'><i class='icon icon-eye'></i></a>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }

    public function renderPageItem(Page $page)
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

    private function renderDropdown(Page $page)
    {

        $config = app("config");
        $templates = app("templates")->all(); // TODO: change to list only supported child templates for this template

        if (count($templates) > 1) {

            $output = '<div class="dropdown">';
            $output .= '<div class="button dropdown-button"><i class="icon icon-plus "></i></div>';
            $output .= '<div class="menu">';

            foreach ($templates as $t) {
                $output .= "<a class='item' href='{$config->urls->root}{$config->adminUrl}/pages/new/{$t->name}/{$page->directory}'>{$t->name}</a>";
            }

            $output .= '</div>';
            $output .= '</div>';

            return $output;
        } else {
            if (count($templates)) {

                $output = "<a class='button' href='" . app('config')->urls->root . app(
                        "config"
                    )->adminUrl . "/pages/new/" . $templates[0]->name . "/" . $page->directory . "'><i class='icon icon-plus'></i></a>";
                return $output;
            }
        }


    }

    public function render()
    {
        $output = $this->renderPageItem($this->rootPage);
        $output = "<div class='page-tree'><ul class='page-tree-list page-tree-root'>{$output}</ul></div>";
        return $output;
    }

}