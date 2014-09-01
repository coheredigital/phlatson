$(function () {

    var $field = $(".field");
    var $fieldInput = $field.find("input,select,textarea");

    $fieldInput.focus(function(){

        $(this).closest(".field").addClass("focus");

    });

    $fieldInput.blur(function(){

        $(this).closest(".field").removeClass("focus");

    });


    var $dropdown = $(".dropdown");
    $dropdown.click(function(){
        $(this).toggleClass("open");
    });
});

var tabber = new HashTabber(options = {
    classActive: 'active',
    classData: 'tabs',
    classNav: 'menu-tabs',
    dataDefault: 'data-tabs-default',
    dataId: 'data-tabs-id',
    dataPair: 'data-tabs-pair',
});
tabber.run();