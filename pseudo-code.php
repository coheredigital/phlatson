<?php

namespace Phlatson;

$app = new App('/path/to/app-name');
$app->path(); // retuns the full validated path

$app->get("/pages/about"); // returns Page('/about')

// add read only data from another site
// allow sharing to write but not by default
$app->addSharedData($appData);
// this is how i can handle multisite eventually
// for now focus on single site

$folder = new AppData($app, '/pages');
$app->addData($folder);

// AppData
$folder->get('/about') // get item at about path

$folder = new Folder($app, '/relative/path');

$data = new DataFile('/relative/path', $app);

$app->pages->get('/')->create('new-page');
$app->pages('/')->create('about');
$app->getPage('/')->createChild('about');
$app->data('pages/about');

// -----------------------------------------------------------
// Finder
// -----------------------------------------------------------
$finder->map('Page', '/site/pages');
// use __call() magic method to allow
$finder->getPage('/about-us'); // OR
$finder->page('/about-us');
$finder->field('title');

// get from app
$app->page('/something/page')->url();
$fields = $app->template('default')->fields();
$fields->get('title');

// switch sites or get other sites data
$phlatson->app('site-name')->template('default')->fields();
$phlatson->site('site-name')->page('/');

// -----------------------------------------------------------
// Page variations
// -----------------------------------------------------------
$page->load("autosave");
$page->load("draft");
$page->load("revision_82382948722");
// alias
$page->revisions()->get('82382948722');
$page->revision('82382948722');

$page->saveAs('draft');

$page->language('en')->title;

/**
 * Storage for languages
 * separate files
 * data_en.json
 * data_fr.json
 */

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
