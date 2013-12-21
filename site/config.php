<?php

if(!defined("JPAGES")) die();


$config->adminUrl = "admin";

$config->timezone = 'America/New_York';


$config->sessionName = 'xpages';
$config->sessionExpireSeconds = 3600;


$config->debug = false;

// whether or not the saved data should be formatted (human readable) on save 
$config->formattedXML = true;