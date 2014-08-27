$(document).ready(function () {

    var $fieldlist = $(".FieldtypeFields .field-list");
    var $fieldselect = $(".FieldtypeFields select");
    $fieldlist.sortable({
        containerSelector: '.FieldtypeFields',
        itemSelector: '.item',
        placeholder: '<div class="sortable-placeholder"/>',
        distance: 5
    });
    $fieldselect.change( function() {
        var $this = $(this);
        var fieldName = $(this).val();
        var item = "<div class='item'>" + fieldName + "</div>"
        $fieldlist.append(item);
        $this.children("[value='" + fieldName + "']").remove();
    });

});

