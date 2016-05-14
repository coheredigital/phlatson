<?php

class MarkupPageTree extends Extension
{

    public $rootPage;
    public $admin;
    public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page

    protected function setup()
    {
        $this->admin = $this->api("admin");
        $this->api('config')->scripts->add("{$this->url}{$this->className}.js");
    }


    protected function renderPageTitle(Page $page)
    {
        $output .= "<div class='page-tree-item'>";
        if($page->isEditable()){
            $output .= "<span class='page-tree-item-title'><a href='{$page->urlEdit}'>{$page->title}</a></span>";
        }
        else {
            $output .= "<span class='page-tree-item-title'>{$page->title}</span>";
        }
        $output .= $this->renderPageControls($page);
        $output .= "</div>";
        return $output;
    }

    protected function renderPageControls(Page $page){
        $view = $page->template->settings->pageTreeView;

        $output = "<div class='page-tree-item-buttons'>";

        if($page->isViewable()) $output .= "<a class='page-tree-item-button' target='_blank' href='{$page->url}'>View</a>";

        if(is_array($view) && $view['type'] == "list"){
            $url = $this->admin->route->url . ltrim($page->url, '/');
            $output .= "<a class='page-tree-item-button' href='{$url}'>List</a>";
            if($this->admin) $this->admin->subnav->add($page);
        }
        else{
            $output .= $this->renderDropdown($page);
        }
        $output .= "</div>";
        return $output;
    }

    protected function renderPageItem(Page $page)
    {
        $output = $this->renderPageTitle($page);
        $class = "page-tree-single";


        var_dump($page->template->settings->pageTreeView);
        if ($page->template->settings->pageTreeView) {

        } else {
            if (count($page->children)) {
                $output .= $this->renderPageList($page->children);
                $class = "page-tree-group";
            }
        }

        $output = "<li class='{$class} page-tree-group'>{$output}</li>";
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

        $templates = $this->api("templates")->all(); // TODO: change to list only supported child templates for this template

        if (count($templates) > 1) {

            $output = '<div class="dropdown page-tree-item-button">';
            $output .= '<div class="dropdown-button">New</div>';
            $output .= '<div class="menu">';

            foreach ($templates as $t) {

                $rootUrl = $this->api("admin")->route->url;

                $output .= "<a class='item' href='{$rootUrl}pages/new/{$t->name}/{$page->directory}'>{$t->name}</a>";
            }

            $output .= '</div>';
            $output .= '</div>';

            return $output;
        } else {
            if (count($templates)) {

                $output = "<a class='button' href='" . $this->api('config')->urls->root . $this->api(
                        "config"
                    )->adminUrl . "/pages/new/" . $templates[0]->name . "/" . $page->directory . "'><i class='icon fa fa-plus'></i></a>";
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