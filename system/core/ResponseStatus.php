<?php


namespace Flatbed;
class ResponseStatus
{


    protected $code = 200;

    protected $valid_codes = [
        // Info
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirect
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',

        // Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];



    public function __construct($code, $message = null)
    {
        $this->code($code);
        if (null === $message) {
            $message = $this->valid_codes[$code];
        }
        $this->message = $message;
    } 

    public function code( ?int $code = null)
    {
        if ($code !== null) {
            $this->code = $code;
            return $this;
        }
        return $this->code;
    }

    public function message() : string
    {
        return $this->valid_codes[$this->status];
    }



}