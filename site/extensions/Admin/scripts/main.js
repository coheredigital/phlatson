$(function () {

    var $field = $(".field");
    var $fieldInput = $field.children("input,select,textarea");

    $fieldInput.focus(function(){

        $(this).closest(".field").addClass("focus");

    });

    $fieldInput.blur(function(){

        $(this).closest(".field").removeClass("focus");

    });

});