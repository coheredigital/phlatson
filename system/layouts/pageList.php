<?php
$home = api("pages")->get("/");
$pageList = $extensions->get("MarkupPageList");
$pageList->rootPage = $home;
$output = "<div class='container'>" . $pageList->render() . "</div>";