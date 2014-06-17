$(function  () {
    $("ul.page-tree-root").sortable({
    	placeholder: '<li class="placeholder"/>',
    	distance: 5,
    	handle: ".reorder",
    	isValidTarget: function  (item, container) {
    	  if(container.el.hasClass("page-tree-root"))
    	    return false
    	  else {
    	    return true
    	  }
    	}
    });
})