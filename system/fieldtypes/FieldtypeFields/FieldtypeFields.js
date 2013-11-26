$(document).ready(function() {
	$gridContainer = $(".FieldtypeFields_fieldsGrid");
	$fieldItem = $(".FieldtypeFields_fieldItem");
   
	var gridWidth = $gridContainer.innerWidth();
	var colWidth = Math.floor(gridWidth / 12);

    $gridContainer.sortable({

        grid: [colWidth, 1]
    });
    
    $fieldItem
    .resizable({
        handles: 'e',
        containment: "parent",
        grid: [ colWidth, 0 ],
        minWidth: colWidth
    })
    .on( "resizestart", function( event, ui ) {
    	$(this)
    	.css("margin-left","-1px") // prevents "jumping" behavior
    	.addClass("resizing")
    	.children(".ui-resizable-handle")
    	.addClass("active"); 

    })
    .on( "resizestop", function() {
    	
		var $this = $(this);
		var $colCount = $this.find(".colCount span");
		var w = $this.width();
		var colNew = Math.round(w / colWidth);


		$this
		.removeClass( function(index, css) {
		    return (css.match (/\bcol-\S+/g) || []).join(' ');
		})
		.removeClass("resizing")
		.addClass('col-'+colNew)
		.attr({
			'style': null,
			'data-columns': colNew
		})
		.children(".ui-resizable-handle")
    	.removeClass("active");

    	$colCount.text(colNew);

    } );
    
    function FieldtypeFields_setSizes(){
    	gridWidth = $gridContainer.innerWidth();
    	colWidth = gridWidth / 12;
    	$fieldItem.resizable( "option", "grid", [ colWidth, 0 ] );
    	$fieldItem.resizable( "option", "minWidth", colWidth );

    	$gridContainer.sortable( "option", "grid", [ colWidth, 1 ] );

    	console.log(colWidth);
    }



    var FieldtypeFields_time = null;
    $(window).bind('resize', function() {

        if (FieldtypeFields_time) clearTimeout(FieldtypeFields_time);
        FieldtypeFields_time = setTimeout(FieldtypeFields_setSizes, 200);
    });


});

