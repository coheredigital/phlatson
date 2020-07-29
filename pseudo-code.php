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


// -----------------------------------------------------------
// API Page creation
// -----------------------------------------------------------

// (INSTANTIATED)
$page = new Page('/about/contact-us', $template, $parent);
$page->save();


// RELATIONAL (object alternate)
// (?optional URI?) name of any object is inferred from a field otherwise, setting URI allows skipping manually setting parent
// requires validating existence of parent on init
$child = new Page("/parent/name-here");
$child->template(new Template("article")); // template allows data to be set
$child->parent($parent); // parent will be need to be checked if it exists, must be set before save, Template validate parent

// field values
$child->title = "These are the Field data for the template";
$child->content = "A very short article"; // $child->set('published',929672343);
$child->published = 929672343; // $child->set('published',929672343);

// key methods
$child->rename("new-name-here"); // name of any object is inferred from a field by default
$child->rename($child->title); // name of any object is inferred from a field by default
$child->save(); // ??MAYBE NOT ALLOWED??, Page does not exist, save merges with exist data
$child->overwrite(); // replaces / creates new

// required fields must be set, will be validated before save
// validation with be provide by passing DataContainer (JsonObject) to $
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
 * - Page (front facing viewable object)
 * - Template (defines the field used, the data type returned)
 * - Field (defines the fieldtype, how data is stored)
 *
 */

// maybe the idea on App objects?
$app = new Phlatson();
$app->addDataLocation("Page","/site/pages/");
$app->addDataLocation("Page","/site/pages/");
// another sharing core
$app2 = new App("/core/Phlatson");
$app2->addDataLocation("Page","/site-other/pages/");
$app2->addDataLocation("Fieldtypes","/site/fieldtypes/"); // shared with $app
$app2 = $phlatson->new()->addDataLocation("Page","/site-other/pages/"); // alternate syntax

// Phlatson class can be the glue
$phlatson->init("/core/Phlatson"); // pass root ?
$phlatson = new Phlatson(); // or none, the default data doesn't move
$phlatson = new Phlatson("/core/data"); // alternate to override
$phlatson->app("name", "/site-name"); // add a site location. I think I want to support multi-site from the start


/**
 * App object
 * think of the $site or $app object as the glue
 * this is what the index file might look like, the index.php file will be user owned
 */
$app = new App("C:\Users\Adam\Websites\phlatson\site"); // (alternate) point at folder, check for config
$app->domains('domain.com');
$app->alias('www.domain.com');
$app->setDataLocation("/site"); // this assumes default folders. or they are set in config

$phlatson->importApp($app); // stored by domain?