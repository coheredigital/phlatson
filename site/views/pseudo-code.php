<?php

namespace Phlatson;



$page->get('template'); // returns Template

// $page->get("template") 
// $page->template()
// TODO: 
// $template->fields()->get('template')  or   $template->fields('template')


// $dataController->get("FieldtypeTemplate","template")

// api page creation 
// (INSTANTIATED)
$page = new Page('/about/conact-us');
$page->save();

// RELATIONAL (primitives)
$page->addChild("name-here","template", [
	"title" => "These are the Field data for the template",
	"content" => "A very short article",
	"published" => 929672343
]);

// RELATIONAL (object)
$childTemplate = new Template("article");
$child = new Page();
$child->setTemplate(Template $childTemplate);

$page->addChild("name-here","template", [
	"title" => "These are the Field data for the template",
	"content" => "A very short article",
	"published" => 929672343
]);

class DataController extends Phlatson
{


}
