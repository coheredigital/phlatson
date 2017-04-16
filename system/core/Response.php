<?php



class Response extends Flatbed
{

    protected $config;
    protected $request;

    protected $protocol = '1.1';

    protected $chunks = [];

    protected $status;
    protected $format = '';
    // protected $format = 'Content-Type: text/html';

    protected $headers = [];
    protected $cookies = [];
    protected $locked = false;
    protected $sent = false;

    // TODO :  move the ResponseFormat class
    protected $common_formats = [
        'html' => 'text/html',
        'txt' => 'text/plain',
        'css' => 'text/css',
        'js' => 'application/x-javascript',
        'xml' => 'application/xml',
        'rss' => 'application/rss+xml',
        'atom' => 'application/atom+xml',
        'json' => 'application/json',
        'jsonp' => 'text/javascript'
    ];

    public function __construct( Request $request, Config $config = null)
    {
        $this->request = $request;
        
        // set default response status
        $this->status = new ResponseStatus(200);

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
            throw new FlatbedException('Response is locked, must be unlocked before being modified');
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

    public function sendBody() : self
    {
        echo (string) $this->body;
        return $this;
    }


    public function send($override = false)
    {
        $this->flush($override);
        exit();
    }

    public function flush($override = false)
    {

        if (headers_sent() && !$override) {
            throw new FlatbedException("Response already sent: {$this->format}");
        }

        // If no format was set use the request format
        // TODO : implement

        // set default values for code and format if not set
        $this->format = $this->format ?? $this->request->format;

        $this->sendHeaders();
        $this->sent = true;

        // build the output
        $out = implode('', $this->chunks);
        $this->chunks = []; // clear body content chunks
        echo ($out);
        return $this;
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
    public function redirect( string $url, int $code = 302) : self
    {
        $this->code($code);
        $this->header('Location', $url);
        $this->lock();
        return $this;
    }


}