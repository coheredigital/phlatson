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
        include 'output.php';
    }

    protected function renderCodeSnippet($file, $line)
    {

        $code = file($file);

        $lineStart = $line - 10;
        $lineEnd = $line + 10;


        $output = "";
        $position = $lineStart;
        while($position < $lineEnd){
            $class = "";
            // highlight line before as well as this is often the offending line
            if($position == $line) $class = "class='highlight'";


            $lineNumber = $position;
            $codeline = htmlspecialchars($code[$position]);
            $output .= "<code $class>{$lineNumber}{$codeline}</code>";
            $position++;
        }

        $output = "<pre class='fbe-code language-php php'>$output</pre>";
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

    protected function renderPageStyles($link = false)
    {
      if ($link) {
        $file = "/system/core/FlatbedException/" . get_class($this) . ".css";
        $styles = "<link href='$file' rel='stylesheet' type='text/css'>";
      }
      else {
        $styles = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . get_class($this) . ".css");
        $styles = "<style>$styles</style>";
      }


      return $styles;

    }

}
