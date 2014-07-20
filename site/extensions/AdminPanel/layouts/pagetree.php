<?php
$home = api::get("pages")->get("/");
$pageList = $extensions->get("MarkupPageList");
$pageList->rootPage = $home;
$output->main = "<div class='container'>" . $pageList->render() . "</div>";