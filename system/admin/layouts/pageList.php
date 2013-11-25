<?php
$home = $pages->get("/");

$pageList = new \markup\PageList;



$pageList->rootPage = $home;			
$output = $pageList->render();
