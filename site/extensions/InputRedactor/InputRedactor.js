$(function () {
    $('.input.InputRedactor').redactor({
        buttons: ['formatting', '|', 'bold', 'italic', 'deleted', '|',
            'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
            'image', 'file', 'table', 'link', '|', '|', 'alignment', '|', 'horizontalrule','|', 'fullscreen'],
        paragraphy: false,
        plugins: ['fullscreen']
    });
});