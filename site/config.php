<?php

if(!defined("XPAGES")) die();


$config->adminUrl = "admin";

$config->timezone = 'America/New_York';


$config->sessionName = 'xpages';
$config->sessionExpireSeconds = 3600;


$config->debug = true;

// whether or not the saved data should be formatted (human readable) on save 
$config->formattedXML = true;