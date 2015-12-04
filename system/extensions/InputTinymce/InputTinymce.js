$(function () {
    tinymce.init({
        selector: 'textarea.InputTinymce',
        menubar: false,
        statusbar: false,
        // min_height: 200,
        element_format : 'html',
        resize: true,
        plugins: "autoresize advlist fullscreen image imagetools link",
        autoresize_bottom_margin: 20,
        autoresize_min_height: 250,
        autoresize_max_height: 1200,
        toolbar: 'undo redo | styleselect | bold italic | link image | fullscreen',
    });
});