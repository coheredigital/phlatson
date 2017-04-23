<?php
namespace Flatbed;

/*

Controller loaded automatically based on the matching template
    example: Template: article loads Controller: /contollers/article.php
    or method spcific if defined: /contollers/article.post.php

Controllers can change the page that is returned
    request to /blog/2017/04/27/the-new-site-is-online
    would be a segment that matched to blog template / page
    but the Controller can intercept the request and match it to the blog post /blog/20170427-the-new-site-is-online
    returning it with its template and page etc
    this is handled by $this->page() method



*/

class Controller extends Flatbed
{

    protected $request;
    protected $template;

    final public function __construct(Template $template, Request $request)
    {
        $this->request = $request;
    }


    protected function page() : Page
    {

    }

}