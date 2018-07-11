<?php 
namespace Phlatson;
$home = new Page('/');
?>
<nav class="site-navigation">
<?php foreach ($home->children() as $p) : ?>
	<a class="item" href="<?= $p->url ?>"><?= $p->title ?></a>
<?php endforeach; ?>
</nav>