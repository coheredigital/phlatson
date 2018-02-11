$(function () {
    $("ul.page-tree-root").sortable({
        placeholder: '<li class="placeholder"/>',
        //distance: 5,
        handle: ".icon",
        isValidTarget: function (item, container) {
            if (container.el.hasClass("page-tree-root"))
                return false
            else {
                return true
            }
        },
        //onDrop: function  (item, targetContainer, _super) {
        //    var clonedItem = $('<li/>').css({height: 0})
        //    item.before(clonedItem)
        //    clonedItem.animate({'height': item.height()})
        //
        //    item.animate(clonedItem.position(), function  () {
        //        clonedItem.detach()
        //        _super(item)
        //    })
        //}
    });

})