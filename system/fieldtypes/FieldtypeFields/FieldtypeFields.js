$(document).ready(function() {
	$gridContainer = $(".FieldtypeFields_fieldsGrid");
	$fieldItem = $(".FieldtypeFields_fieldItem");
   
	var gridWidth = $gridContainer.innerWidth();
	var colWidth = Math.floor(gridWidth / 12);

    $fieldItem.each(function(){
        var c = $(this).attr('data-columns');
        var w = colWidth * c;
        $(this).width(w);
    });


$gridContainer.sortable({
    placeholder: "FieldtypeFields_fieldItem placeholder"
})
.on( "sortstart", function( event, ui ) {
    var w  = ui.helper.outerWidth()- 1;
    ui.placeholder.width(w);
} );
    
    $fieldItem
    .resizable({
        handles: 'e',
        containment: "parent",
        grid: [ colWidth, 0 ],
        minWidth: colWidth,

    })
    .on( "resizestart", function( event, ui ) {
        $(this)
        .addClass("resizing")
        .removeClass( function(index, css) {
            return (css.match (/\bcol_\S+/g) || []).join(' ');
        })
        .css("margin-left","-1px") // prevents "jumping" behavior due to rounding errors
        .addClass("resizing");

    })
    .on( "resizestop", function() {
        
        var $this = $(this);
        var $colCount = $this.find(".colCount span");
        var w = $this.width();
        var colNew = Math.round(w / colWidth);


        $this
        .removeClass("resizing")
        .addClass('col_'+colNew)
        .attr({
            'data-columns': colNew,
            'data-ss-colspan': colNew
        });

        $colCount.text(colNew);
        applyShapeshift();
    } );
    
    function FieldtypeFields_setSizes(){
    	gridWidth = $gridContainer.innerWidth();
    	colWidth = gridWidth / 12;
    	$fieldItem.resizable( "option", "grid", [ colWidth, 0 ] );
    	$fieldItem.resizable( "option", "minWidth", colWidth );
    }



    var FieldtypeFields_time = null;
    $(window).bind('resize', function() {

        if (FieldtypeFields_time) clearTimeout(FieldtypeFields_time);
        FieldtypeFields_time = setTimeout(FieldtypeFields_setSizes, 200);
    });


});

