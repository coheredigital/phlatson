<?php
$home = $pages->get("/");

$pageList = new \markup\PageList;
$pageList->baseUrl = $config->adminUrl."/";	


$pageList->rootPage = $home;			
$output = $pageList->render();
