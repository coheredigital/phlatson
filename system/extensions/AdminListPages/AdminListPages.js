$(function(){

	var $pageTree = $(".page-tree");

	$pageTree.on("click", ".page-tree-item-button-expand",function(event){

		$this = $(this);
		var $group = $this.closest(".page-tree-group");
		var $list = $this.closest(".page-tree-list");
		var $listWrapper = $group.children(".page-tree-list-wrapper");
		var url = $this.attr('href');

		// get ajax request if not yet loaded
		if (!$listWrapper.hasClass("is-loaded")) {
			$listWrapper.load(url + " .page-tree .page-tree-list-wrapper .page-tree-list", function(){
				$listWrapper.addClass("is-loaded");
			});
		}
		else{
			// $listWrapper.toggleClass("is-open");
			$group.toggleClass("is-open");
		}
		


		// if (!$group.hasClass("is-open")) {
		// 	$listWrapper.load(url + " .page-tree .page-tree-list-wrapper .page-tree-list", function(){
		// 		$group.addClass("is-open is-loaded");
		// 	});
		// }


		event.preventDefault();
	});

});