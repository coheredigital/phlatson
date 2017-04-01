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
            <span class="fbe-label">Exception</span>
            <span class="fbe-message"><?php echo $this->getMessage() ?></span>
          </div>
          <div class="fbe-file"><?php echo $this->getFile() ?></div>
        </div>
        <div class="trace-list">
          <?php foreach ($this->getTrace() as $key => $trace): ?>
            <div class="trace <?php echo $key == 0 ? 'open' : '' ?>">
              <div class="trace-title">
                <div class="trace-file"><?= $trace['file'] ?></div>
                <div class="trace-line"><?= $trace['line'] - 1 ?></div>
              </div>
              <div class="trace-code">
                <?php echo $this->renderCodeSnippet($trace['file'], $trace['line'] - 1) ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/components/prism-php.min.js"></script>
    <script>// hljs.initHighlightingOnLoad();</script>
  </body>
</html>
