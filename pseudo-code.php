<?php


// -----------------------------------------------------------
// Finder
// -----------------------------------------------------------
$finder->addMapping("Page", "/site/pages");
// use __call() magic method to allow
$finder->getPage("/about-us"); // OR
$finder->page("/about-us");
$finder->field("title");
$finder->field("title");

$fields->get("title");

// get from app
$app->page("/something/page")->url();
$app->template("default")->fields();
// switch sites
$app('site-name')->template("default")->fields();
$phlatson->app('site-name');
// -----------------------------------------------------------
// API Page creation
// -----------------------------------------------------------

// (INSTANTIATED)
$page = new Page('/about/contact-us', $template, $parent);
$page->save();

$page->template()->view()->data([
	"title" => "Awsome page title.",
	"author" => "Adam Spruijt"
])->render();

// RELATIONAL (DataDataDataDataDataDataObject alternate)
// (?optional URI?) name of any DataDataObject is inferred from a field otherwise, setting URI allows skipping manually setting parent
// requires validating existence of parent on init
$child = new Page("/parent/name-here");
$child->template(new Template("article")); // template allows data to be set
$child->parent($parent); // parent will be need to be checked if it exists, must be set before save, Template validate parent

// field values
$child->title = "These are the Field data for the template";
$child->content = "A very short article"; // $child->set('published',929672343);
$child->published = 929672343; // $child->set('published',929672343);

// key methods
$child->rename("new-name-here"); // name of any DataObject is inferred from a field by default
$child->rename($child->title); // name of any DataObject is inferred from a field by default
$child->save(); // ??MAYBE NOT ALLOWED??, Page does not exist, save merges with exist data
$child->overwrite(); // replaces / creates new

// required fields must be set, will be validated before save
// validation with be provide by passing DataContainer (JsonDataObject) to $
$template->validate($jsonData);

// ultra simple example
$home->createChild("name-of-page","template")


// -----------------------------------------------------------
// Page languages
// -----------------------------------------------------------
$page->language("en")->title;

/**
 * Storage for languages
 * separate files
 * data_en.json
 * data_fr.json
 */


// -----------------------------------------------------------
// Multisite
// -----------------------------------------------------------
$page->language("en")->title;

$phlatson->site('site-name')->getPage("/");

/**
 * Storage for languages
 * separate files
 * data_en.json
 * data_fr.json
 */


/**
 * The core DataObject in Phlatson are
 *
 * - Page (front facing viewable DataObject)
 * - Template (defines the field used, the data type returned)
 * - Field (defines the fieldtype, how data is stored)
 * - Fieldtype
 * - User
 */



 class DataManager {



	protected $paths = [

		"C:/Users/Adam/Websites/phlatson/Phlatson/data/pages",
		"C:/Users/Adam/Websites/phlatson/site/pages",

		"C:/Users/Adam/Websites/phlatson/Phlatson/data/fields",
		"C:/Users/Adam/Websites/phlatson/site/fields",

		"C:/Users/Adam/Websites/phlatson/Phlatson/data/templates",
		"C:/Users/Adam/Websites/phlatson/site/templates",

		"C:/Users/Adam/Websites/phlatson/Phlatson/data/users",
		"C:/Users/Adam/Websites/phlatson/site/users",

	];



 }

 $userDataManager->get("adam");
//  $dataManager->get("User::adam");

$dataFolder->get('adam');