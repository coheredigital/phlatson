$(function(){


	$("input").focus(function(){
		$(this).closest(".panel").addClass("panel-primary");
	});

	$("input").blur(function(){
		$(this).closest(".panel").removeClass("panel-primary");
	});

});