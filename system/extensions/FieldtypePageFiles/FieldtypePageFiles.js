// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
//var myDropzone = new Dropzone("div#files");
Dropzone.autoDiscover = false;


var myDropzone = new Dropzone("div#files", {
    clickable: ".FieldtypePageFiles-dragndrop",
    previewsContainer: "#PageFilesList",
    thumbnailWidth: 32,
    thumbnailHeight: 32,
    previewTemplate:
        '<div class="item dz-file-preview">' +
        '<img class="ui image" height="32" width="32" data-dz-thumbnail >' +
         "<div class='right floated red ui icon button'><i class='trash icon'></i></div>" +
        '<div class="content">' +
            '<div class="header" data-dz-name></div>' +
            '<div class="description" data-dz-size></div>' +
        '</div>' +
        '</div>'
});


