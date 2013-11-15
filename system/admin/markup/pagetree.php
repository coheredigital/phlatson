<?php
$home = $pages->get("/");

function PageTreeItemTitle(Page $p){
	return 	"<div class='page-tree-item'>
				<a href='http://localhost/XPages/admin/page/edit/?page=$p->directory'>
				$p->title
				</a>
				<span>
				
				</span>
			</div>";
}

$homeItem = PageTreeItemTitle($home);
if (count($home->children)){
	foreach ($home->children as $p) {
		$item .= "<li class='page-tree-group'>";
		$item .= PageTreeItemTitle($p);
			if ($p->children){
				$item .= "<ul class='page-tree-list'>";
				foreach ($p->children as $p) {
					$item .= "<li class='page-tree-group'>";
					$item .= PageTreeItemTitle($p);
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
