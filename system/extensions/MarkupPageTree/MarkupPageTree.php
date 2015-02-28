<?php

class MarkupPageTree extends Extension
{

    public $rootPage;
    public $admin;
    public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page

    protected function setup()
    {
        $this->admin = app("admin");
        app('config')->scripts->add($this->url . "{$this->className}.js");
    }


    protected function renderPageTitle(Page $page)
    {
        $output .= "<div class='page-tree-item'>";
        $output .= "<i class='icon icon-circle'></i><span class='page-tree-item-title'>{$page->title}</span>";
        $output .= $this->renderPageControls($page);
        $output .= "</div>";
        return $output;
    }

    protected function renderPageControls(Page $page){
        $view = $page->template->view;

        $output = "<div class='page-tree-item-buttons'>";

        if($view){
            $output .= "<a class='page-tree-item-button' href='{$this->admin->route->url}pages{$page->url}'><i class='icon icon-list'></i> List</a>";
        }
        else{
            $output .= "<a class='page-tree-item-button' href='{$this->admin->route->url}" . "pages/edit/" . $page->directory . "'><i class='icon icon-pencil'></i></a>";
            $output .= "<a class='page-tree-item-button' target='_blank' href='{$page->url}'><i class='icon icon-eye'></i></a>";
            $output .= $this->renderDropdown($page);

        }
        $output .= "</div>";
        return $output;
    }

    protected function renderPageItem(Page $page)
    {
        $output = $this->renderPageTitle($page);
        $class = "page-tree-single";


        if ($page->template->view) {

        } else {
            if (count($page->children())) {



                $output .= $this->renderPageList($page->children);
                $class = "page-tree-group";


            }
        }


        $output = "<li class='$class page-tree-group'>{$output}</li>";
        return $output;
    }

    protected function renderPageList($pages)
    {
        $output = "";
        foreach ($pages as $p) {
            $output .= $this->renderPageItem($p);
        }
        $output = "<ul class='page-tree-list'> {$output} </ul>";
        return $output;
    }

    protected function renderDropdown(Page $page)
    {

        $templates = app("templates")->all(); // TODO: change to list only supported child templates for this template

        if (count($templates) > 1) {

            $output = '<div class="dropdown page-tree-item-button">';
            $output .= '<div class="dropdown-button"><i class="icon icon-plus "></i></div>';
            $output .= '<div class="menu">';

            foreach ($templates as $t) {

                $rootUrl = app("admin")->route->url;

                $output .= "<a class='item' href='{$rootUrl}pages/new/{$t->name}/{$page->directory}'>{$t->name}</a>";
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