// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
//var myDropzone = new Dropzone("div#files");
Dropzone.autoDiscover = false;


var myDropzone = new Dropzone("div#files", {
    clickable: ".FieldtypePageFiles-dragndrop",
    previewsContainer: "#PageFilesList",
    thumbnailWidth: 32,
    thumbnailHeight: 32,
    previewTemplate: '<div class="item dz-file-preview">' +
        '<img class="thumbnial" height="64" width="64" data-dz-thumbnail >' +
//        "<div class='right floated button'><i class='icon icon-times'></i></div>" +
        '<div class="content dz-details">' +
        '<div class="header dz-filename" data-dz-name></div>' +
        '<div class="description dz-size" data-dz-size></div>' +
        '</div>' +
        '<div class="dz-progress"><div class="dz-upload" data-dz-uploadprogress></div></div>' +
        '</div>'
});


