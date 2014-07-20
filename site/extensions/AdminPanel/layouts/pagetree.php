<?php
$home = api("pages")->get("/");
$pageList = $extensions->get("MarkupPageList");
$pageList->rootPage = $home;
$pageList->adminPanel = $this;
$output->main = "<div class='container'>" . $pageList->render() . "</div>";