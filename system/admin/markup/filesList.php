<?php 
foreach ($files->all() as $file) {
	$output .= "<p>{$file->filename} | {$file->type} | $file->size</p>";
}