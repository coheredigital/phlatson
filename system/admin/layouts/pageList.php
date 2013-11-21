<?php
$home = $pages->get("/");

function PageTreeItemTitle(Page $p, $adminUrl){
	return 	"<div class='page-tree-item'>
				<a href='{$adminUrl}page/edit/?page={$p->directory}'>
				$p->title
				</a>
				<span>
				
				</span>
			</div>";
}

$homeItem = PageTreeItemTitle($home, $adminUrl);
if (count($home->children)){
	foreach ($home->children as $p) {
		$item .= "<li class='page-tree-group'>";
		$item .= PageTreeItemTitle($p, $adminUrl);
			if ($p->children){
				$item .= "<ul class='page-tree-list'>";
				foreach ($p->children as $p) {
					$item .= "<li class='page-tree-group'>";
					$item .= PageTreeItemTitle($p, $adminUrl);
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
