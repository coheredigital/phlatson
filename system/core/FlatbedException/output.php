<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <?= $this->renderPageStyles(true) ?>
  </head>
  <body id="FlatbedException">

    <div class="fbe-container">
      <div class="fbe-wrapper">
        <div class="fbe-title">
          <div class="fbe-heading">
            <span class="fbe-label"><?= get_class($this) ?></span>
            <span class="fbe-message"><?= $this->getMessage() ?></span>
          </div>
          <div class="fbe-file"><?= $this->getFile() ?></div>
        </div>
        <div class="trace-list">
          <?php foreach ($this->getTrace() as $key => $trace): ?>
            <div class="trace">
                <input type="checkbox" <?= $key === 0 ? 'checked' : '' ?> name="trace" id="trace-<?= $key ?>">
	        	<label for='trace-<?= $key ?>' class="trace-title">
                    <span class="trace-call">
                        <?= $this->getFunctionString($trace) ?>(<span class="trace-call-arguments"><?= $this->getArgumentsString($trace) ?></span>)

                    </span>
                    <span class="trace-file"><?= $trace['file'] ?></span>
                    <span class="trace-line"><?= $trace['line'] - 1 ?></span>
				</label>
                <div class="trace-code">
                    <?= $this->renderCodeSnippet($trace['file'], $trace['line'] - 1) ?>
                </div>

            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/components/prism-php.min.js"></script>
  </body>
</html>
