<?php


namespace Flatbed;
class Response extends Flatbed
{

    protected $request;
    public $page;
    public $template;

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
    protected $named_segments = [];


    public function __construct( Request $request, Page $page)
    {
        $this->request = $request;
        $this->page = $page;
        $this->template = $page->template;

        // set raw request segment array
        $segment = str_remove_prefix($request->url, $page->url);
        if ($segment = trim($segment,"/")) {
            $this->segments = explode("/", $segment);
        }

        // set named segment array if segment_map is defined
        if (count($this->segments) && $segemnt_map = $this->template->setting('segment_map')) {
            $this->named_segments = $this->getNamedSegments($segemnt_map);
        }

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

    public function sendBody() : self
    {
        echo (string) $this->body;
        return $this;
    }


    public function send($override = false)
    {
        $this->flush($override);
        exit;
    }

    public function flush($override = false)
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


        // TODO : temp profiling, remove later, maybe implement built in version
        $profile = $this->api('profile');
        $profile->end = microtime(true);
        $profile->time = round(($profile->end - $profile->start), 2);
        $this->chunks[] = "<!-- Page created in $profile->time seconds. (" . getMemoryUse() .") -->";

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
        return $named ? $this->named_segments : $this->segments;
    }

    protected function getNamedSegments(string $segment_map) {
        
        $named_segments = [];

        // trim open and close slashes in case they were included
        $segment_map = trim($segment_map, "/");
        $segment_map = explode("/", $segment_map );

        foreach ($segment_map as $key => $map) {
            
            // break map segment into its components
            $map = explode(":", $map);
            list($type, $name) = $map;

            // type are all lowercase
            $type = strtolower($type);

            // default name to the type requested
            $name = $name ? $name : $type;

            $position = $key+1;
            $segmemt = $this->segment($position);

            switch ($type) {
                // simple scalar type
                case 'any':
                case 'string':
                    $named_segments["$name"] = $segmemt;
                    break;
                case 'int':
                    // special case to handle zero
                    if ($segmemt == "0") {
                        $segmemt = (int) $segmemt;
                    }
                    else {
                        $segmemt = (int) $segmemt ?: null;
                    }
                    $named_segments["$name"] = $segmemt;
                    break;
                case 'all':
                    $segmemt = $this->segment($position, null);
                    $named_segments["$name"] = $segmemt;
                    break 2;

                // advanced Object types
                // TODO : just for testing
                case 'subview':
                    $subview = $this->api('views')->get("{$this->template->name}.{$segmemt}");
                    $named_segments["$name"] = $subview;
                    break;
                case 'extension':
                    $extension = $this->api('extensions')->get($segmemt);
                    $named_segments["$name"] = $extension;
                    break;
                case 'field':
                    $field = $this('fields')->get($segmemt);
                    $named_segments["$name"] = $field;
                    break;
                case 'template':
                    $template = $this('templates')->get($segmemt);
                    $named_segments["$name"] = $template;
                    break;
                    break;
                case 'user':
                    $user = $this('users')->get($segmemt);
                    $named_segments["$name"] = $user;
                    break;
                case 'page':
                    $segmemt = $this->segment($position, null);
                    $page = $this('pages')->get($segmemt);
                    $named_segments["$name"] = $page;
                    break 2;
                // api access
                case 'api':
                    
            }

        }
        return $named_segments;
        
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