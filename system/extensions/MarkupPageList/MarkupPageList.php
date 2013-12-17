<?php

class MarkupPageList {

	public $rootPage;
	public $postTypes = array(); // array representing posts types or "Page Tables" that will be offers at top of the page


	public function renderPageTitle(\Page $page){
		$output .= "<div class='page-tree-item'>";
		$output .= "<a href='".api('config')->urls->root.api("config")->adminUrl."/pages/edit/?name=".$page->directory."'>".$page->title."</a>";
		$output .= "</div>";
		return $output;
	}

	public function renderPageItem(\Page $page){
		$output = $this->renderPageTitle($page);
		if (count($page->children)) {
			$output .= $this->renderPageList($page->children);
		}
		$output = "<li class='page-tree-group'> {$output} </li>";
		return $output;
	}

	private function renderPageList($pages){
		$output = "";
		foreach ($pages as $p) {
			$output .= $this->renderPageItem($p);
		}
		$output = "<ul class='page-tree-list'> {$output} </ul>";
		return $output;
	}

	public function render(){
		$output = $this->renderPageItem($this->rootPage);
		$output = "<div class='page-tree'><ul class='page-tree-list'>{$output}</ul></div>";
		return $output;
	}

}