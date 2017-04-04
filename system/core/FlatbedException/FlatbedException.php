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

    public function render( $config )
    {
        ob_clean(); // clear exising markup
        include 'output.php';
    }

    /**
     * pass in a trace to get a simple function call as string
     * @param  array  $trace
     * @return string
     */
    protected function getFunctionString( array $trace ) : string
    {
        $string = '';
        if ( isset($trace['class']) ) $string .= $trace['class'];
        if ( isset($trace['type']) ) $string .= $trace['type'];
        if ( isset($trace['function']) ) $string .= $trace['function'];
        return $string;
    }

    protected function getArgumentsString( array $trace ) : string
    {
        $arguments = [];

        if (isset($trace['args']) && count($trace['args'])) {
            // $string = print_r($trace['args']);
            foreach ($trace['args'] as $value) {
                $arguments[] = gettype($value);
            }

        }

        if ( count($arguments) == 0 ) return '';

        $string = implode(' , ', $arguments);
        return " $string ";
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

}
