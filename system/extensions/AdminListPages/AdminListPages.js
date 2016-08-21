$(function(){

	var $pageTree = $(".page-tree");

	$pageTree.on("click", ".page-tree-item-button-expand",function(event){

		$this = $(this);
		var $group = $this.closest(".page-tree-group");
		var $listWrapper = $group.children(".page-tree-list-wrapper");
		var url = $this.attr('href');
		$listWrapper.load(url + " .page-tree .page-tree-list-wrapper .page-tree-list");

		event.preventDefault();
	});

});