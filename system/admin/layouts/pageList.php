<?php
$home = $pages->get("/");
$pageList = $extensions->get("MarkupPageList");
$pageList->rootPage = $home;			
$output = $pageList->render();