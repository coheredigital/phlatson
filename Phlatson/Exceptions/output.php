<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <?=$this->renderPageStyles(true)?>
  </head>
  <body id="PhlatsonException">

    <div class="fbe-container">
      <div class="fbe-wrapper">
        <label for='trace' class="fbe-title">
          <span class="fbe-heading">
            <span class="fbe-label"><?=get_class($this)?></span><br>
            <span class="fbe-message" style="font-size: 16px; font-weight:bold;"><?=$this->getMessage()?></span>
          </span>
          <span class="fbe-file"><?=$this->getFile()?></span>
          <span class="trace-line"><?=$this->line?></span>
        </label>
        <div class="trace-list">
          
          <div class="trace">
            <input type="checkbox" checked name="trace" id="trace">
                <div class="trace-code">
                    <?=$this->renderCodeSnippet($this->file, $this->line)?>
                </div>
            </div>

          <?php foreach ($this->getTrace() as $key => $trace): ?>
            <div class="trace">
                <input type="checkbox" name="trace" id="trace-<?=$key?>">
                  <label for='trace-<?=$key?>' class="trace-title">
                    <span class="trace-call">
                        <?=$this->getFunctionString($trace)?>(<span class="trace-call-arguments"><?=$this->getArgumentsString($trace)?></span>)
                    </span>
                    <span class="trace-file"><?=$trace['file']?></span>
                    <span class="trace-line"><?=$trace['line']?></span>
                </label>
                <div class="trace-code">
                    <?=$this->renderCodeSnippet($trace['file'], $trace['line'])?>
                </div>

            </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/components/prism-php.min.js"></script>
  </body>
</html>
