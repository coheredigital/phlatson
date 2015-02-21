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

        $message = "<pre id='title'>Exception</pre>";
        $message .= "<pre id='message'>" . trim($this->getMessage()) . "</pre>";
        $message .= "<pre id='file'>" . $this->getFile() . "</pre>";
        if (app("config")->debug) {
            $message .= $this->renderTraceTable();
        }
        $output = $this->renderPage($message);
        return $output;
    }

    public function renderTraceTable()
    {

        $table = "<div id='trace'><table>";
        $table .= "<tr><th>line</th><th>file</th><th>class@method('args')</th></tr>";

        foreach($this->getTrace() as $trace){
            $table .= "<tr>";
            $table .= "<td>" . $trace['line'] . "</td>";
            $table .= "<td>" . $trace['file'] . "</td>";
            $table .= "<td>"
                . $trace['class']
                . $trace['type']
                . $trace['function']
                . "("
//                . implode(",", $trace['args'] )
                .  ")</td>";
            $table .= "</tr>";

        }

        $table .= "</table></div>";

        return $table;

    }

    protected function log(){

        $message =  trim($this->getMessage());
        $file = $this->getFile();
        $trace = $this->getTrace();
        $trace = $trace[0];
        $line = "#" . $trace['line'] . " (" . $trace['class'] . $trace['type'] . $trace['function'] . ")";
        $log = "Exception: $message in ($file) on $line";
        app("logger")->add("error", $log);

    }

    protected function renderPageStyles()
    {
        $styles = "<style>
                    *{font-family: 'Courier New', monospace; margin: 0;}
                    body{background: #222;}
                    #title{background: #e55550; color: #fff; padding: 20px; font-size: 26px; }
                    #message{ background: #fff; color: #999; padding: 13px 20px; font-size: 16px; white-space: normal;}
                    #file{ color: #fff; padding: 30px 20px; font-size: 14px;}
                    #trace{ padding: 0 20px;}
                    #trace table{ width: 100%; color: #aaa; font-size: 14px; text-align: left; border-collapse: collapse;}
                    #trace th{ font-weight: bold; border-bottom: 1px solid #444 !important; padding: 4px;}
                    #trace td{ padding: 4px;}
        </style>";
        return $styles;
    }

    protected function renderPage($message)
    {
        $output = "<html>";
        $output .= $this->renderPageStyles();
        $output .= "<body>$message</body>";
        $output .= "<html>";
        return $output;
    }

}