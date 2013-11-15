<?php
$home = $pages->get("/");

function PageTreeItemTitle($title, $url){
	return 	"<div class='page-tree-item'>
				<a href='http://localhost/XPages/admin/edit/?page=$url'>
				$title
				</a>
			</div>";
}

$homeItem = PageTreeItemTitle($home->title, $home->directory);
if (count($home->children)){
	foreach ($home->children as $p) {
		$item .= "<li class='page-tree-group'>";
		$item .= PageTreeItemTitle($p->title, $p->directory);
			if ($p->children){
				$item .= "<ul class='page-tree-list'>";
				foreach ($p->children as $p) {
					$item .= "<li class='page-tree-group'>";
					$item .= PageTreeItemTitle($p->title, $p->directory);
					$item .= "</li>";
				}
				$item .= "</ul>";
			}
		$item .= "</li>";
	}
}

$output = "{$homeItem}<ul class='page-tree-list'> {$item} </ul>";

$pageTreeRoot = "<div class='page-tree'> 
					<ul class='page-tree-list'>
						<li class='page-tree-group'>
							{$output} 
						</li>
					</ul>
				</div>";
				
$output = $pageTreeRoot;
