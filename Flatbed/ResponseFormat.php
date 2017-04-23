<?php
namespace Flatbed;

/*

Placeholder class

*/

class ResponseFormat
{

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



    public function __construct( Request $request, Page $page)
    {


    }


}