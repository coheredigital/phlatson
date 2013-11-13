
<?php

class FieldtypeTextarea extends Fieldtype{
		// return "
		// <script type='text/javascript'>
		// $(function()
		// {
		//     $('#Input_$name').redactor({
		//         imageGetJson: images
		//     });
		// });
		// </script>
		// <textarea class='form-control' name='$name' id='Input_$name' cols='30' rows='10'>{$value}</textarea>
		// ";

		// return "
		// <script type='text/javascript'>
		// 	tinymce.init({
		// 	    selector: '#Input_$name',
		// 	    menubar: false,
		// 	    plugins: [
	 //                'advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker',
	 //                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
	 //                'table contextmenu directionality emoticons template textcolor paste fullpage textcolor'
  //       		],
  //       		toolbar1: 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | table | styleselect formatselect | fullscreen',
		//         toolbar2: 'bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media'
		// 	 });
		// </script>
		// <textarea class='form-control' name='$name' id='Input_$name' cols='30' rows='10'>{$value}</textarea>
		// ";


	public function getInput($name, $value){
		return "
		<script type='text/javascript'>
		$(function()
		{
		    $('#Input_$name').redactor({
		        imageGetJson: images
		    });
		});
		</script>
		<textarea class='field-input' name='$name' id='Input_$name' cols='30' rows='10'>{$value}</textarea>
		";
	}

}