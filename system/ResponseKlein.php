<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/chriso/klein.php
 * @license     MIT
 */

namespace Klein;

/**
 * Response
 */
class ResponseKlein
{


    /**
     * Properties
     */

    /**
     * The default response HTTP status code
     *
     * @type int
     */
    protected static $default_status_code = 200;

    /**
     * The HTTP version of the response
     *
     * @type string
     */
    protected $protocol_version = '1.1';

    /**
     * The response body
     *
     * @type string
     */
    protected $body;

    /**
     * HTTP response status
     *
     * @type HttpStatus
     */
    protected $status;

    /**
     * HTTP response headers
     *
     * @type HeaderDataCollection
     */
    protected $headers;

    /**
     * HTTP response cookies
     *
     * @type ResponseCookieDataCollection
     */
    protected $cookies;

    /**
     * Whether or not the response is "locked" from
     * any further modification
     *
     * @type boolean
     */
    protected $locked = false;

    /**
     * Whether or not the response has been sent
     *
     * @type boolean
     */
    protected $sent = false;

    /**
     * Whether the response has been chunked or not
     *
     * @type boolean
     */
    public $chunked = false;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * Create a new AbstractResponse object with a dependency injected Headers instance
     *
     * @param string $body          The response body's content
     * @param int $status_code      The status code
     * @param array $headers        The response header "hash"
     */
    public function __construct($body = '', $status_code = null, array $headers = array())
    {
        $status_code   = $status_code ?: static::$default_status_code;

        // Set our body and code using our internal methods
        $this->body($body);
        $this->code($status_code);

        $this->headers = new HeaderDataCollection($headers);
        $this->cookies = new ResponseCookieDataCollection();
    }

    /**
     * Get (or set) the HTTP protocol version
     *
     * Simply calling this method without any arguments returns the current protocol version.
     * Calling with an integer argument, however, attempts to set the protocol version to what
     * was provided by the argument.
     *
     * @param string $protocol_version
     * @return string|AbstractResponse
     */
    public function protocolVersion($protocol_version = null)
    {
        if (null !== $protocol_version) {
            // Require that the response be unlocked before changing it
            $this->requireUnlocked();

            $this->protocol_version = (string) $protocol_version;

            return $this;
        }

        return $this->protocol_version;
    }

    /**
     * Get (or set) the response's body content
     *
     * Simply calling this method without any arguments returns the current response body.
     * Calling with an argument, however, sets the response body to what was provided by the argument.
     *
     * @param string $body  The body content string
     * @return string|AbstractResponse
     */
    public function body($body = null)
    {
        if (null !== $body) {
            // Require that the response be unlocked before changing it
            $this->requireUnlocked();

            $this->body = (string) $body;

            return $this;
        }

        return $this->body;
    }

    /**
     * Returns the status object
     *
     * @return \Klein\HttpStatus
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Returns the headers collection
     *
     * @return HeaderDataCollection
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Returns the cookies collection
     *
     * @return ResponseCookieDataCollection
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * Get (or set) the HTTP response code
     *
     * Simply calling this method without any arguments returns the current response code.
     * Calling with an integer argument, however, attempts to set the response code to what
     * was provided by the argument.
     *
     * @param int $code     The HTTP status code to send
     * @return int|AbstractResponse
     */
    public function code($code = null)
    {
        if (null !== $code) {
            // Require that the response be unlocked before changing it
            $this->requireUnlocked();

            $this->status = new HttpStatus($code);

            return $this;
        }

        return $this->status->getCode();
    }

    /**
     * Prepend a string to the response's content body
     *
     * @param string $content   The string to prepend
     * @return AbstractResponse
     */
    public function prepend($content)
    {
        // Require that the response be unlocked before changing it
        $this->requireUnlocked();

        $this->body = $content . $this->body;

        return $this;
    }

    /**
     * Append a string to the response's content body
     *
     * @param string $content   The string to append
     * @return AbstractResponse
     */
    public function append($content)
    {
        // Require that the response be unlocked before changing it
        $this->requireUnlocked();

        $this->body .= $content;

        return $this;
    }

    /**
     * Check if the response is locked
     *
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Require that the response is unlocked
     *
     * Throws an exception if the response is locked,
     * preventing any methods from mutating the response
     * when its locked
     *
     * @throws LockedResponseException  If the response is locked
     * @return AbstractResponse
     */
    public function requireUnlocked()
    {
        if ($this->isLocked()) {
            throw new LockedResponseException('Response is locked');
        }

        return $this;
    }

    /**
     * Lock the response from further modification
     *
     * @return AbstractResponse
     */
    public function lock()
    {
        $this->locked = true;

        return $this;
    }

    /**
     * Unlock the response from further modification
     *
     * @return AbstractResponse
     */
    public function unlock()
    {
        $this->locked = false;

        return $this;
    }

    /**
     * Generates an HTTP compatible status header line string
     *
     * Creates the string based off of the response's properties
     *
     * @return string
     */
    protected function httpStatusLine()
    {
        return sprintf('HTTP/%s %s', $this->protocol_version, $this->status);
    }

    /**
     * Send our HTTP headers
     *
     * @param boolean $cookies_also Whether or not to also send the cookies after sending the normal headers
     * @param boolean $override     Whether or not to override the check if headers have already been sent
     * @return AbstractResponse
     */
    public function sendHeaders($cookies_also = true, $override = false)
    {
        if (headers_sent() && !$override) {
            return $this;
        }

        // Send our HTTP status line
        header($this->httpStatusLine());

        // Iterate through our Headers data collection and send each header
        foreach ($this->headers as $key => $value) {
            header($key .': '. $value, false);
        }

        if ($cookies_also) {
            $this->sendCookies($override);
        }

        return $this;
    }

    /**
     * Send our HTTP response cookies
     *
     * @param boolean $override     Whether or not to override the check if headers have already been sent
     * @return AbstractResponse
     */
    public function sendCookies($override = false)
    {
        if (headers_sent() && !$override) {
            return $this;
        }

        // Iterate through our Cookies data collection and set each cookie natively
        foreach ($this->cookies as $cookie) {
            // Use the built-in PHP "setcookie" function
            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpire(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->getSecure(),
                $cookie->getHttpOnly()
            );
        }

        return $this;
    }

    /**
     * Send our body's contents
     *
     * @return AbstractResponse
     */
    public function sendBody()
    {
        echo (string) $this->body;

        return $this;
    }

    /**
     * Send the response and lock it
     *
     * @param boolean $override             Whether or not to override the check if the response has already been sent
     * @throws ResponseAlreadySentException If the response has already been sent
     * @return AbstractResponse
     */
    public function send($override = false)
    {
        if ($this->sent && !$override) {
            throw new ResponseAlreadySentException('Response has already been sent');
        }

        // Send our response data
        $this->sendHeaders();
        $this->sendBody();

        // Lock the response from further modification
        $this->lock();

        // Mark as sent
        $this->sent = true;

        // If there running FPM, tell the process manager to finish the server request/response handling
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        return $this;
    }

    /**
     * Check if the response has been sent
     *
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }


    /**
     * Sets a response header
     *
     * @param string $key       The name of the HTTP response header
     * @param mixed $value      The value to set the header with
     * @return AbstractResponse
     */
    public function header($key, $value)
    {
        $this->headers->set($key, $value);

        return $this;
    }


    /**
     * Tell the browser not to cache the response
     *
     * @return AbstractResponse
     */
    public function noCache()
    {
        $this->header('Pragma', 'no-cache');
        $this->header('Cache-Control', 'no-store, no-cache');

        return $this;
    }

    /**
     * Redirects the request to another URL
     *
     * @param string $url   The URL to redirect to
     * @param int $code     The HTTP status code to use for redirection
     * @return AbstractResponse
     */
    public function redirect($url, $code = 302)
    {
        $this->code($code);
        $this->header('Location', $url);
        $this->lock();

        return $this;
    }



    /**
     * Sends a file
     *
     * It should be noted that this method disables caching
     * of the response by default, as dynamically created
     * files responses are usually downloads of some type
     * and rarely make sense to be HTTP cached
     *
     * Also, this method removes any data/content that is
     * currently in the response body and replaces it with
     * the file's data
     *
     * @param string $path      The path of the file to send
     * @param string $filename  The file's name
     * @param string $mimetype  The MIME type of the file
     * @return Response
     */
    public function file($path, $filename = null, $mimetype = null)
    {
        $this->body('');
        $this->noCache();

        if (null === $filename) {
            $filename = basename($path);
        }
        if (null === $mimetype) {
            $mimetype = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        }

        $this->header('Content-type', $mimetype);
        $this->header('Content-length', filesize($path));
        $this->header('Content-Disposition', 'attachment; filename="'.$filename.'"');

        $this->send();

        readfile($path);

        return $this;
    }



}