<?php

foreach ($page->children() as $p) {
    $title = "<a class='header' href='{$p->url}'>{$p->title}</a>";
    if ($p->description) {
        $description = "<div class='description'>{$p->description}</div>";
    }
    $output->main .= "<div class='item'><div class='content'>{$title}{$description}</div></div>";
}

$output->main = "<div class='container'><div class='ui list'>{$output->main}</div></div>";
