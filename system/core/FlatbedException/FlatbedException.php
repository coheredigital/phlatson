<?php


class FlatbedException extends Exception
{

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString()
    {
        return $this->render();
    }

    public function render($config)
    {

        // $this->log();


        $message = "<pre id='title'>Exception<pre id='file'>" . $this->getFile() . "</pre></pre>";

        $message .= "<pre id='message'>" . trim($this->getMessage()) . "</pre>";
        if ($config->debug) {
            $message .= $this->renderCodeSnippet();
            $message .= $this->renderTraceTable();
        }




        $output = $this->renderPage($message);
        return $output;
    }

    public function renderTraceTable()
    {

        $table = "<div id='trace'><table>";
        $table .= "<thead><tr><th>line</th><th>file</th><th>class@method('args')</th></tr></thead><tbody>";

        foreach ($this->getTrace() as $trace) {
            $table .= "<tr>";
            $table .= "<td>" . $trace['line'] . "</td>";
            $table .= "<td>" . $trace['file'] . "</td>";
            $table .= "<td><pre class='php'>"
                . $trace['class']
                . $trace['type']
                . $trace['function']
                . "("
//                . implode(",", $trace['args'] )
                . ")</pre></td>";
            $table .= "</tr>";

        }

        $table .= "</tbody></table></div>";

        return $table;

    }

    protected function renderCodeSnippet()
    {

        $trace = $this->getTrace();
        $trace = $trace[0];

        $file = $trace["file"];
        $line = $trace["line"];

        if(!isset($trace["file"])) return false;

        $code = file($file);

        $lineStart = $line - 5;
        $lineEnd = $line + 5;


        $output = "";
        $position = $lineStart;
        while($position < $lineEnd){
            $class = "";
            // highlight line before as well as this is often the offending line
            if($position == $line || $position == ($line - 1)) $class = "class='highlight'";


            $lineNumber = $position;
            $codeline = htmlspecialchars($code[$position]);
            $output .= "<code $class>{$lineNumber}{$codeline}</code>";
            $position++;
        }

        $output = "<pre id='code' class='php'>$output</pre>";
        return $output;
    }

    protected function log($logger)
    {

        if(!$logger) return false;

        $message = trim($this->getMessage());
        $file = $this->getFile();
        $trace = $this->getTrace();
        $trace = $trace[0];
        $line = "#" . $this->getLine() . " (" . $trace['class'] . $trace['type'] . $trace['function'] . ")";
        $log = "Exception: $message in ($file) on $line";
        $logger->add("error", $log);

    }

    protected function renderPageStyles()
    {
      $styles = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . get_class($this) . ".css");
      $styles = "<style>$styles</style>";
      return $styles;

    }

    protected function renderPage($message)
    {
        $output = "<html>";
        $output .= "<head>";
        $output .= '<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>';
        $output .= '<script>hljs.initHighlightingOnLoad();</script>';
        $output .= "<head>";
        $output .= "</head>";
        $output .= "</head>";
        $output .= $this->renderPageStyles();
        $output .= "<body>$message</body>";
        $output .= "<html>";
        return $output;
    }

}
