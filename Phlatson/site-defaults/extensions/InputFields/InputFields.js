$(document).ready(function () {

    var $fieldlist = $(".InputFields .field-list");
    var $fieldselect = $(".InputFields select");

    $fieldlist.sortable({
        containerSelector: '.FieldtypeFields',
        itemSelector: '.item',
        placeholder: '<div class="sortable-placeholder"/>',
        distance: 5
    });
    $fieldselect.change( function() {
        var $this = $(this);
        var field = $this.closest(".field").attr("data-fieldname");
        var name = $(this).val();
        var input = "<input type='hidden' name='" + field + "[" + name + "]' value='1' >";
        var item = "<div class='item'>" + name + input + "</div>"
        $fieldlist.append(item);
        $this.children("[value='" + name + "']").remove();
    });

});

