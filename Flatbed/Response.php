<?php
namespace Flatbed;
class Response
{

    // protected $request;
    public $page;
    // public $template;
    // public $controller;

    protected $protocol = '1.1';

    protected $chunks = [];

    protected $status;
    protected $format = '';
    // protected $format = 'Content-Type: text/html';

    protected $headers = [];
    protected $cookies = [];
    protected $locked = false;
    protected $sent = false;

    protected $segments = [];

	protected function removePrefix ($string, $prefix)
	{
	    if (substr($string, 0, strlen($prefix)) == $prefix) {
	        $string = substr($string, strlen($prefix));
	    }
	    return $string;
	}

    public function __construct()
    {
        // $this->request = $request;
        // $this->page = $page;
        // $this->template = $page->template;


        // set raw request segment array
        $segment = $this->removePrefix($request->url, $page->url);
        if ($segment = trim($segment,"/")) {
            $this->segments = explode("/", $segment);
        }


        // set default response status
        $this->status = new ResponseStatus(200);

        // // once the response has been fully instantiated the controller can be created
        // $this->controller = new Controller($this);

        // // excute the controller ??  TODO : this needs to move, the structure of all this SUCKS
        // $this->controller->run($request);

    }

    public function protocol($protocol = null)
    {
        if (null !== $protocol) {
            // Require that the response be unlocked before changing it
            $this->verifyUnlocked();

            $this->protocol = (string) $protocol;

            return $this;
        }

        return $this->protocol;
    }


    public function append( string $content = '')
    {
        $this->verifyUnlocked();
        $this->chunks[] = $content;
        return $this;
    }


    public function status()
    {
        return $this->status->code();
    }

    public function headers()
    {
        return $this->headers;
    }

    public function cookies()
    {
        return $this->cookies;
    }

    public function code($code = null)
    {
        if ($code !== null) {
            // Require that the response be unlocked before changing it
            $this->verifyUnlocked();
            $this->status = new ResponseStatus($code);
            return $this;
        }

        return $this->status;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function verifyUnlocked()
    {
        if ($this->isLocked()) {
            throw new Exceptions\FlatbedException('Response is locked, must be unlocked before being modified');
        }
        return $this;
    }


    public function lock()
    {
        $this->locked = true;
        return $this;
    }

    public function unlock()
    {
        $this->locked = false;
        return $this;
    }


    protected function httpStatus()
    {
        // TODO : implement ResponseStatus class
        return trim("HTTP/{$this->protocol} {$this->status->code()}");
    }


    public function sendHeaders($send_cookies = true) : self
    {

        if (headers_sent()) {
            return $this;
        }

        // Send our HTTP status line
        header($this->httpStatus());

        // Iterate through our Headers data collection and send each header
        foreach ($this->headers as $key => $value) {
            header($key .': '. $value, false);
        }

        if ($send_cookies) {
            $this->sendCookies($override);
        }

        return $this;
    }

    public function sendCookies($force = false)
    {
        if (headers_sent() && !$override) {
            return $this;
        }

        // TODO : implement ResponseCookies class to hold cookie iterator
        return $this;
    }

    public function render() : string
    {
        return $this->page->render();
    }


    public function send($override = false)
    {
        // TODO : temp disabled for testing
        // if (headers_sent() && !$override) {
        //     throw new Exceptions\FlatbedException("Response already sent: {$this->format}");
        // }

        // If no format was set use the request format
        // TODO : implement

        // set default values for code and format if not set
        $this->format = $this->format ?? $this->request->format;

        $this->sendHeaders();
        $this->sent = true;

        // build the output
        $out = $this->render();

        $this->chunks = []; // clear body content chunks
        echo ($out);
        return $this;
        exit;
    }



    /**
     * Sets a response header
     *
     * @param string $key       name of the response header
     * @param string $value      value to set the header with
     * @return Response
     */
    public function header(string $key, string $value) : self
    {
        $this->headers[$key] = $value;
        return $this;
    }


    public function cookie( string $name, $value, $expiry = null, string $path = '/', ?string $domain = null, bool $secure = false, bool $httponly = false) : self
    {
        // set default expiry
        if (null === $expiry) {
            $expiry = time() + (3600 * 24 * 30);
        }

        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        return $this;
    }


    public function disableCache() : self
    {
        $this->header('Pragma', 'no-cache');
        $this->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        $this->header('Expires', 'Thu, 26 Feb 1970 20:00:00 GMT');

        return $this;
    }

    /**
     * redirect the response, default to code 302 (Found)
     * @param  string  $url  the redirect destination
     * @param  integer $code [description]
     * @return Response
     */
    public function redirect( string $url, int $code = 302)
    {
        $this->unlock();
        $this->code($code);
        $this->header('Location', $url);
        return $this;
        exit;
    }

    /**
     * get the segment portion at the request position, counting from left to right
     * @param  int    $position
     * @return string           the URL portion at the posisiton set
     */
    public function segment(int $start, ?int $limit = 1) : ?string
    {

        $index = $start - 1;
        $segments = array_slice($this->segments, $index, $limit);
        $segment = implode("/", $segments);
        $segment = trim($segment);

        return isset($segment) && $segment != '' ? $segment : null;
    }

    /**
     * get the segment portion at the request position, counting from left to right
     * @param  int    $position
     * @return string           the URL portion at the posisiton set
     */
    public function segments( bool $named = false) : array
    {
        return $this->segments;
    }

    public function get(string $name)
    {
        switch ($name) {
            case 'segment':
                return implode("/", $this->segments) . "/";
            default:
                return null;
        }
    }

}
