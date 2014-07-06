<?php

foreach ($page->children() as $p) {
    $title = "<a class='header' href='{$p->url}'>{$p->title}</a>";
    if ($p->description) {
        $description = "<div class='description'>{$p->description}</div>";
    }
    $output .= "<div class='content'><div class='item'>{$title}{$description}</div></div>";
}

$output = "<div class='container'><div class='ui list'>{$output}</div></div>";
