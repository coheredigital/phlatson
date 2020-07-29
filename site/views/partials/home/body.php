<div class="page-header">
	<div class="container">
		<h1>Phlatson <span>CMS</span></h1>
		<h5>The PHP Flat-file JSON CMS</h5>
	</div>
</div>
<div class="feature">
	<div class="container">
		<div class="grid -align-center ">
			<div class="column -desktop-4">
				<h4>JSON Data</h4>
				<p>Vestibulum feugiat diam a eu parturient duis dis a sem netus fringilla condimentum tellus vestibulum </p>
			</div>
			<div class="column -desktop-8">
				<pre><code>{
  "title": "Canada",
  "content": "Canada is a country located in the northern part of North America. Its ten provinces and three territories extend from the Atlantic to the Pacific",
  "template": "article",
  "modified": 1462636496,
}</code></pre>
			</div>
		</div>

	</div>
</div>

<div class="feature">
	<div class="container">
		<div class="grid -align-center ">
			<div class="column -desktop-4">
				<h4>Dead Simple Templates</h4>
				<p>Vestibulum feugiat diam a eu parturient duis dis a sem netus fringilla condimentum tellus vestibulum risus adipiscing suspendisse</p>
			</div>
			<div class="column -desktop-8">
				<?= $this->render("/partials/code-block", [
					"string" => '<article>
								<h2><?= $page->title ?></h2>
								<p><?= $page->content ?></p>
								<?php if ($page->template == "article"): ?>
									<h6>Last updated: <?= $page->modified->format("Y/d/d") ?></h6>
								<?php endif; ?>
								</article>'
				]); ?>
			</div>
		</div>

	</div>
</div>