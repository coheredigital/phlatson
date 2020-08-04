<?php

$app = new App('/path/to/app-name');

$app->path();

$folder = new AppData($app, '/relative/path');

$data = new DataFile('/relative/path', $app);

// -----------------------------------------------------------
// Finder
// -----------------------------------------------------------
$finder->map('Page', '/site/pages');
// use __call() magic method to allow
$finder->getPage('/about-us'); // OR
$finder->page('/about-us');
$finder->field('title');
$finder->field('title');

$fields->get('title');

// get from app
$app->page('/something/page')->url();
$app->template('default')->fields();
// switch sites
$app('site-name')->template('default')->fields();
$phlatson->app('site-name');

// -----------------------------------------------------------
// Page languages
// -----------------------------------------------------------
$page->language('en')->title;

/**
 * Storage for languages
 * separate files
 * data_en.json
 * data_fr.json
 */

// -----------------------------------------------------------
// Multisite
// -----------------------------------------------------------
$page->language('en')->title;

$phlatson->site('site-name')->getPage('/');

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

 class DataManager
 {
 	protected $paths = [
 		'C:/Users/Adam/Websites/phlatson/Phlatson/data/pages',
 		'C:/Users/Adam/Websites/phlatson/site/pages',

 		'C:/Users/Adam/Websites/phlatson/Phlatson/data/fields',
 		'C:/Users/Adam/Websites/phlatson/site/fields',

 		'C:/Users/Adam/Websites/phlatson/Phlatson/data/templates',
 		'C:/Users/Adam/Websites/phlatson/site/templates',

 		'C:/Users/Adam/Websites/phlatson/Phlatson/data/users',
 		'C:/Users/Adam/Websites/phlatson/site/users',
 	];
 }

 $userDataManager->get('adam');
//  $dataManager->get("User::adam");

$dataFolder->get('adam');
