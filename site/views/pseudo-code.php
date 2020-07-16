<?php

namespace Phlatson;

// -----------------------------------------------------------
// API Page creation 
// -----------------------------------------------------------

# 1
// (INSTANTIATED)
$page = new Page('/about/conact-us');
$page->save();

# 2
// RELATIONAL (primitives)
$page->addChild("name-here","template", [
	"title" => "These are the Field data for the template",
	"content" => "A very short article",
	"published" => 929672343
]);

# 3
// RELATIONAL (object)
$childTemplate = new Template("article");
$child = new Page();
$child->setTemplate($childTemplate); // template allows data to be set
$child->name = "name-here"; // (optional) name of any object is inferred from a field by default
$child->title = "These are the Field data for the template";
$child->content = "A very short article"; // $child->set('published',929672343);
$child->published = 929672343; // $child->set('published',929672343);

$page->addChild($child);


# 4
// RELATIONAL (object alternate)
$child = new Page();
$child->template(new Template("article")); // template allows data to be set
$child->name("name-here"); // (optional) name of any object is inferred from a field by default
$child->parent($parent); // parent will be need to be checked if it exists, must be set before save, Template validate parent

$child->title = "These are the Field data for the template";
$child->content = "A very short article"; // $child->set('published',929672343);
$child->published = 929672343; // $child->set('published',929672343);
$child->save(); // ??MAYBE NOT ALLOWED??, Page does not exist, save merges with exist data
$child->overwrite(); // replaces / creates new

// required fields must be set, will be validated before save 
// validation with be provide by passing DataContainer (JsonObject) to $
$template->validate($jsonData);


