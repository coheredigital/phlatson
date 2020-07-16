<?php 
namespace Phlatson;

$home = $finder->get("Page", "/");

?>
<nav class="site-navigation">
<?php foreach ($home->children() as $p) : ?>
	<a class="uppercase mr1 pt1 inline-block" href="<?= $p->url() ?>"><?= $p->title ?></a>
<?php endforeach; ?>
</nav>