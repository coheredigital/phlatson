$(function(){


	var opts = {
		basePath: 'http://localhost/xpages/system/fieldtypes/FieldtypeMarkdown/epiceditor',
		theme: {
			base: '/themes/base/epiceditor.css',
			preview: '/themes/preview/preview-dark.css',
			editor: '/themes/editor/epic-dark.css'
		},
		button: {
		   preview: true,
		   fullscreen: true,
		   bar: "auto"
		 }
	}
	// var editor = new EpicEditor(opts).load();

	$('.FieldtypeMarkdown').each(function (i) {
	  opts.container = this;
	  // opts.container = 'Input_markdown';
	  var editor = new EpicEditor(opts).load();
	});

});