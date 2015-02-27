<?php


class FlatbedException extends Exception
{

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // some code

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {

        $this->log();


        $message = "<pre id='title'>Exception<pre id='file'>" . $this->getFile() . "</pre></pre>";

        $message .= "<pre id='message'>" . trim($this->getMessage()) . "</pre>";
        if (app("config")->debug) {
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

    protected function log()
    {

        if(!app("logger")) return false;

        $message = trim($this->getMessage());
        $file = $this->getFile();
        $trace = $this->getTrace();
        $trace = $trace[0];
        $line = "#" . $this->getLine() . " (" . $trace['class'] . $trace['type'] . $trace['function'] . ")";
        $log = "Exception: $message in ($file) on $line";
        app("logger")->add("error", $log);

    }

    protected function renderPageStyles()
    {
        $styles = "<style>
                    *{font-family: 'Courier New', monospace !important; margin: 0;}
                    body{background: #222; width: 100%; overflow: hidden; font-size: 16px;}
                    #title{background: #e55550; color: #fff; padding: 20px; font-size: 26px; }
                    #message{ background: #fff; color: #999; padding: 13px 20px; white-space: normal;}
                    #file{ color: #894343; padding: 0; font-size: 16px;}
                    #trace{ padding:20px;}
                    #trace table{ width: 100%; color: #aaa; text-align: left; border-collapse: collapse;}
                    #trace th{ font-weight: bold; border-bottom: 1px solid #333 !important; padding: 8px 4px; color: #ddd;}
                    #trace td{ padding: 4px;}
                    #trace tbody tr:first-child td{ padding: 12px 4px 4px 4px;}
                    #code{ padding: 10px 0; color: #909090; background: #111; line-height: 1.6em; width: 100%; overflow: hidden;}
                    #code > code{ padding: 2px 20px; display: block !important; white-space: pre-wrap; width: 100%;}
                    #code > code > span{ color: #444;}
                    #code .highlight{ background: #000 !important;}
                    #code .hljs{ background: #111; color: #888;}
                    #code .hljs-number{ color: #444; margin-right: 1em;}
                    #code .highlight .hljs-number{ color: #ccc;}
                    #code .hljs-variable{ color: #ff5853;}
                    #code .hljs-keyword{ color: #eee;}
                    #code .hljs-function .hljs-title{ color: #8ba3d8;}

        </style>";
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