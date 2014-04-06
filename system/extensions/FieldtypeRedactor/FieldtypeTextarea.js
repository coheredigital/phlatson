$(function () {
    $('.FieldtypeRedactor').redactor({
        buttons: ['formatting', '|', 'bold', 'italic', 'deleted', '|',
            'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
            'image', 'file', 'table', 'link', '|', '|', 'alignment', '|', 'horizontalrule'],
        paragraphy: false
    });
});