<?php

foreach ($page->children() as $p) {
    $title = "<h4><a href='{$p->url}'>{$p->title}</a></h4>";
    if ($p->description) {
        $description = "<p>{$p->description}</p>";
    }
    $output .= "{$title}{$description}";
}

$output = "<div class='container'><div class='ui list'>{$output}</div></div>";