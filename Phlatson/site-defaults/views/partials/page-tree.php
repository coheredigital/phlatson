<?php

$markupPageList = $this->api("extensions")->get("MarkupPageTree");
$markupPageList->rootPage = $pages->get('/');
$markupPageList->admin = $page;
?>
<div class="container">
    <?= $markupPageList->render() ?>
</div>